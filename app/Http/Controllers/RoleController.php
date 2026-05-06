<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::withCount('users')->get();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:50|unique:roles,nom',
        ]);

        $role = Role::create($validated);

        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => "Création du rôle '{$role->nom}'",
            'adresse_ip' => request()->ip(),
        ]);

        return redirect()->route('roles.index')->with('success', 'Rôle créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $role->load('users');
        return view('roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:50|unique:roles,nom,' . $role->id,
        ]);

        $ancienNom = $role->nom;
        $role->update($validated);

        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => "Modification du rôle '{$ancienNom}' -> '{$role->nom}'",
            'adresse_ip' => request()->ip(),
        ]);

        return redirect()->route('roles.index')->with('success', 'Rôle mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // Sécurité : Empêcher la suppression si des utilisateurs sont associés
        if ($role->users()->count() > 0) {
            return back()->withErrors(['erreur' => "Impossible de supprimer le rôle '{$role->nom}' : des utilisateurs y sont encore associés."]);
        }

        $nom = $role->nom;
        $role->delete();

        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => "Suppression du rôle '{$nom}'",
            'adresse_ip' => request()->ip(),
        ]);

        return redirect()->route('roles.index')->with('success', 'Rôle supprimé.');
    }
}
