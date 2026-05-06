<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatierePremiere extends Model
{
    protected $table = 'matieres_premieres'; // Forcer le nom de la table
protected $fillable = ['nom', 'quantite_stock', 'seuil_alerte'];
}
