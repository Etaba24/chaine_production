<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('role')->orderBy('created_at', 'desc')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id'  => 'required|exists:roles,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => "Création de l'utilisateur '{$user->name}' ({$user->email}) avec le rôle ID: {$user->role_id}",
            'adresse_ip' => request()->ip(),
        ]);

        return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('role');
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id'  => 'required|exists:roles,id',
        ]);

        // Ne mettre à jour le mot de passe que si un nouveau est fourni
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => "Modification de l'utilisateur '{$user->name}' (ID: {$user->id})",
            'adresse_ip' => request()->ip(),
        ]);

        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Sécurité : Empêcher l'auto-suppression
        if ($user->id === auth()->id()) {
            return back()->withErrors(['erreur' => 'Vous ne pouvez pas supprimer votre propre compte.']);
        }

        $nom = $user->name;
        $user->delete();

        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => "Suppression de l'utilisateur '{$nom}'",
            'adresse_ip' => request()->ip(),
        ]);

        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé.');
    }
}
