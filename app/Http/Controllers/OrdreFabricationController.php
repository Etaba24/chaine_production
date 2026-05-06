<?php

namespace App\Http\Controllers;

use App\Models\OrdreFabrication;
use App\Models\AuditLog;
use App\Models\Machine;
use App\Http\Requests\StoreOrdreRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrdreFabricationController extends Controller
{
    public function index()
    {
        $ordres = OrdreFabrication::with(['produit', 'machine', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('ordres.index', compact('ordres'));
    }

    public function store(StoreOrdreRequest $request)
    {
        // Les données sont déjà filtrées et l'utilisateur autorisé grâce au StoreOrdreRequest
        $validated = $request->validated();

        // Vérification métier : La machine est-elle opérationnelle ?
        $machine = Machine::findOrFail($validated['machine_id']);
        if ($machine->statut !== 'en_marche') {
            return back()->withErrors(['machine_id' => 'Action bloquée : Cette machine n\'est pas disponible.']);
        }

        try {
            // Début de la transaction SQL sécurisée
            DB::beginTransaction();

            // 1. Création de l'ordre avec génération de l'UUID pour le QR Code
            $ordre = OrdreFabrication::create([
                'tracking_code' => Str::uuid(),
                'produit_id'    => $validated['produit_id'],
                'machine_id'    => $validated['machine_id'],
                'user_id'       => $validated['user_id'],
                'quantite'      => $validated['quantite'],
                'statut'        => 'en_attente',
            ]);

            // 2. Traçabilité obligatoire (Audit Log)
            AuditLog::create([
                'user_id'    => auth()->id(),
                'action'     => "Création de l'ordre de fabrication ID: {$ordre->id}",
                'adresse_ip' => request()->ip(), // On trace l'IP source
            ]);

            // Tout s'est bien passé, on valide l'écriture en base
            DB::commit();

            // Redirection vers la liste des ordres (route à définir)
            return redirect()->route('ordres.index')->with('success', 'Ordre créé et tracé avec succès.');

        } catch (\Exception $e) {
            // En cas d'erreur (ex: perte de connexion BD), on annule toutes les modifications
            DB::rollBack();
            
            // En tant que pentesteur, on ne retourne jamais l'erreur SQL brute à l'utilisateur final 
            // pour éviter l'énumération ou la fuite d'informations (Information Disclosure)
            return back()->withErrors(['erreur' => 'Une erreur critique est survenue lors de la création de l\'ordre. L\'action a été annulée.']);
        }
    }

    public function trace($tracking_code)
    {
        // On récupère l'ordre et on charge (Eager Loading) les relations pour éviter les requêtes N+1
        // firstOrFail() est crucial : si l'UUID est invalide, ça lève une 404 propre au lieu de planter
        $ordre = OrdreFabrication::with(['produit', 'machine', 'user'])
                    ->where('tracking_code', $tracking_code)
                    ->firstOrFail();

        // On renvoie ces données vers la vue Blade
        return view('trace', compact('ordre'));
    }
    public function create()
    {
        // En entreprise, on ne propose à l'interface que les machines opérationnelles
        $produits = \App\Models\Produit::all();
        $machines = \App\Models\Machine::where('statut', 'en_marche')->get();
        
        // On récupère les utilisateurs qui peuvent être assignés à la production (Ouvriers et Chefs)
        $users = \App\Models\User::whereHas('role', function($q) {
            $q->whereIn('nom', ['ouvrier', 'chef_atelier']);
        })->get();

        return view('ordres.create', compact('produits', 'machines', 'users'));
    }

    public function mesOrdres()
    {
        // On récupère les ordres assignés à l'ouvrier connecté
        $ordres = OrdreFabrication::with(['produit', 'machine'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('ordres.mes_ordres', compact('ordres'));
    }

    public function updateStatutOuvrier(\Illuminate\Http\Request $request, OrdreFabrication $ordre)
    {
        // Sécurité : l'ouvrier ne peut modifier que ses propres ordres
        if ($ordre->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé à cet ordre.');
        }

        $validated = $request->validate([
            'statut' => 'required|in:en_attente,en_cours,termine',
        ]);

        $ordre->update(['statut' => $validated['statut']]);

        // Optionnel : Tracer l'action
        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => "Mise à jour du statut de l'ordre ID: {$ordre->id} vers {$validated['statut']}",
            'adresse_ip' => request()->ip(),
        ]);

        return back()->with('success', 'Statut de l\'ordre mis à jour avec succès.');
    }
}