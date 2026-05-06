<?php

namespace App\Http\Controllers;

use App\Models\MatierePremiere;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatierePremiereController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $matieres = MatierePremiere::orderBy('nom')->get();
        return view('matieres.index', compact('matieres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('matieres.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'            => 'required|string|max:100',
            'quantite_stock' => 'required|numeric|min:0',
            'seuil_alerte'   => 'required|numeric|min:0',
        ]);

        $matiere = MatierePremiere::create($validated);

        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => "Création de la matière première '{$matiere->nom}' (Stock: {$matiere->quantite_stock})",
            'adresse_ip' => request()->ip(),
        ]);

        return redirect()->route('matieres.index')->with('success', 'Matière première créée.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MatierePremiere $matiere)
    {
        return view('matieres.show', compact('matiere'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MatierePremiere $matiere)
    {
        return view('matieres.edit', compact('matiere'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MatierePremiere $matiere)
    {
        $validated = $request->validate([
            'nom'            => 'required|string|max:100',
            'quantite_stock' => 'required|numeric|min:0',
            'seuil_alerte'   => 'required|numeric|min:0',
        ]);

        $matiere->update($validated);

        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => "Modification de la matière première '{$matiere->nom}' (ID: {$matiere->id})",
            'adresse_ip' => request()->ip(),
        ]);

        return redirect()->route('matieres.index')->with('success', 'Matière première mise à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MatierePremiere $matiere)
    {
        $nom = $matiere->nom;
        $matiere->delete();

        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => "Suppression de la matière première '{$nom}'",
            'adresse_ip' => request()->ip(),
        ]);

        return redirect()->route('matieres.index')->with('success', 'Matière première supprimée.');
    }

    /**
     * Ajuster le stock (ajout ou retrait).
     */
    public function ajusterStock(Request $request, MatierePremiere $matiere)
    {
        if (!in_array(auth()->user()->role->nom, ['chef_atelier', 'admin'])) {
            abort(403, 'Accès refusé.');
        }

        // On accepte un nombre positif (ajout) ou négatif (retrait)
        $validated = $request->validate([
            'quantite' => 'required|numeric'
        ]);

        try {
            DB::beginTransaction();

            $matiere->quantite_stock += $validated['quantite'];
            $matiere->save();

            AuditLog::create([
                'user_id'    => auth()->id(),
                'action'     => "Ajustement stock {$matiere->nom} : " . ($validated['quantite'] > 0 ? '+' : '') . "{$validated['quantite']}",
                'adresse_ip' => request()->ip(),
            ]);

            DB::commit();
            return back()->with('success', 'Stock mis à jour.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['erreur' => 'Erreur lors de l\'ajustement du stock.']);
        }
    }
}