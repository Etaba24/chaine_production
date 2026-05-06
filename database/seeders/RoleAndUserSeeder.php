<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Création des Rôles
        $roleAdmin = Role::create(['nom' => 'admin']);
        $roleChef = Role::create(['nom' => 'chef_atelier']);
        $roleOuvrier = Role::create(['nom' => 'ouvrier']);

        // 2. Création des Utilisateurs de test
        // Mot de passe commun pour les tests : 'password'
        
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@usine.com',
            'password' => Hash::make('password'),
            'role_id' => $roleAdmin->id,
        ]);

        User::create([
            'name' => 'Chef Atelier',
            'email' => 'chef@usine.com',
            'password' => Hash::make('password'),
            'role_id' => $roleChef->id,
        ]);

        User::create([
            'name' => 'Ouvrier 1',
            'email' => 'ouvrier@usine.com',
            'password' => Hash::make('password'),
            'role_id' => $roleOuvrier->id,
        ]);
    }
}