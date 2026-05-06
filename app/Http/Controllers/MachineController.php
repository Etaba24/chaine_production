<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MachineController extends Controller
{
    public function index()
    {
        $machines = Machine::all();
        return view('machines.index', compact('machines'));
    }

    public function create()
    {
        return view('machines.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'    => 'required|string|max:100|unique:machines,nom',
            'statut' => 'required|in:en_marche,en_panne,en_maintenance',
        ]);

        $machine = Machine::create($validated);

        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => "Ajout de la machine '{$machine->nom}' (Statut: {$machine->statut})",
            'adresse_ip' => request()->ip(),
        ]);

        return redirect()->route('machines.index')->with('success', 'Machine ajoutée avec succès.');
    }

    public function show(Machine $machine)
    {
        $machine->load('ordres.produit');
        return view('machines.show', compact('machine'));
    }

    public function edit(Machine $machine)
    {
        return view('machines.edit', compact('machine'));
    }

    public function update(Request $request, Machine $machine)
    {
        $validated = $request->validate([
            'nom'    => 'required|string|max:100|unique:machines,nom,' . $machine->id,
            'statut' => 'required|in:en_marche,en_panne,en_maintenance',
        ]);

        $machine->update($validated);

        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => "Modification de la machine '{$machine->nom}' (ID: {$machine->id})",
            'adresse_ip' => request()->ip(),
        ]);

        return redirect()->route('machines.index')->with('success', 'Machine mise à jour.');
    }

    public function destroy(Machine $machine)
    {
        // Sécurité : Empêcher la suppression si des ordres sont liés
        if ($machine->ordres()->count() > 0) {
            return back()->withErrors(['erreur' => "Impossible de supprimer la machine '{$machine->nom}' : des ordres de fabrication y sont associés."]);
        }

        $nom = $machine->nom;
        $machine->delete();

        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => "Suppression de la machine '{$nom}'",
            'adresse_ip' => request()->ip(),
        ]);

        return redirect()->route('machines.index')->with('success', 'Machine supprimée.');
    }

    public function updateStatut(Request $request, Machine $machine)
    {
        // Sécurité : Seul le chef ou l'admin peut modifier l'état
        if (!in_array(auth()->user()->role->nom, ['chef_atelier', 'admin'])) {
            abort(403, 'Accès refusé.');
        }

        $validated = $request->validate([
            'statut' => 'required|in:en_marche,en_panne,en_maintenance'
        ]);

        try {
            DB::beginTransaction();

            $ancienStatut = $machine->statut;
            $machine->update(['statut' => $validated['statut']]);

            AuditLog::create([
                'user_id'    => auth()->id(),
                'action'     => "Statut machine {$machine->nom} : {$ancienStatut} -> {$validated['statut']}",
                'adresse_ip' => request()->ip(),
            ]);

            DB::commit();
            return back()->with('success', 'Statut de la machine mis à jour.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['erreur' => 'Erreur lors de la mise à jour.']);
        }
    }
}