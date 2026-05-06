<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // On supprime ou commente le code par défaut de Laravel qui cause l'erreur
        // User::factory(10)->create();
        // User::factory()->create([ 'name' => 'Test User', ... ]);

        // On lance uniquement notre propre injection sécurisée
        $this->call([
            RoleAndUserSeeder::class,
        ]);
    }
}