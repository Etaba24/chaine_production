<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produits = Produit::orderBy('created_at', 'desc')->get();
        return view('produits.index', compact('produits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('produits.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reference' => 'required|string|max:50|unique:produits,reference',
            'nom'       => 'required|string|max:100',
        ]);

        $produit = Produit::create($validated);

        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => "Création du produit '{$produit->nom}' (Réf: {$produit->reference})",
            'adresse_ip' => request()->ip(),
        ]);

        return redirect()->route('produits.index')->with('success', 'Produit créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Produit $produit)
    {
        $produit->load('ordres');
        return view('produits.show', compact('produit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produit $produit)
    {
        return view('produits.edit', compact('produit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produit $produit)
    {
        $validated = $request->validate([
            'reference' => 'required|string|max:50|unique:produits,reference,' . $produit->id,
            'nom'       => 'required|string|max:100',
        ]);

        $produit->update($validated);

        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => "Modification du produit ID: {$produit->id} (Réf: {$produit->reference})",
            'adresse_ip' => request()->ip(),
        ]);

        return redirect()->route('produits.index')->with('success', 'Produit mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produit $produit)
    {
        $nom = $produit->nom;
        $produit->delete();

        AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => "Suppression du produit '{$nom}'",
            'adresse_ip' => request()->ip(),
        ]);

        return redirect()->route('produits.index')->with('success', 'Produit supprimé.');
    }
}
