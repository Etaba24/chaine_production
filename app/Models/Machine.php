<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = ['nom', 'statut'];

public function ordres() {
    return $this->hasMany(OrdreFabrication::class);
}
}
