<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrdreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // SÉCURITÉ (RBAC) : Seuls les chefs d'atelier et les admins peuvent créer un ordre
        // (On suppose ici que tu as chargé la relation 'role' sur l'utilisateur connecté)
        $role = auth()->user()->role->nom;
        return in_array($role, ['chef_atelier', 'admin']);
    }

    public function rules(): array
    {
        return [
            'produit_id' => 'required|exists:produits,id',
            'machine_id' => 'required|exists:machines,id',
            'user_id'    => 'required|exists:users,id',
            'quantite'   => 'required|integer|min:1',
        ];
    }
}