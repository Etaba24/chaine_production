<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = ['reference', 'nom'];

public function ordres() {
    return $this->hasMany(OrdreFabrication::class);
}
}
