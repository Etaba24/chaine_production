<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdreFabrication extends Model
{
    use HasFactory;

    // Règle stricte : Forcer le nom exact de la table
    protected $table = 'ordres_fabrication';

    // SÉCURITÉ : Liste blanche des champs modifiables par l'utilisateur
    protected $fillable = [
        'tracking_code', 
        'produit_id', 
        'machine_id', 
        'user_id', 
        'quantite', 
        'statut', 
        'date_debut', 
        'date_fin'
    ];

    // --- RELATIONS ---
    
    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}