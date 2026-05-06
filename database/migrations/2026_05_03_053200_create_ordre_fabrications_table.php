<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ordres_fabrication', function (Blueprint $table) {
            $table->id();
            $table->uuid('tracking_code')->unique(); // L'identifiant sécurisé pour le QR Code
            
            // Clés étrangères strictes
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->foreignId('machine_id')->constrained('machines')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            
            $table->integer('quantite');
            $table->enum('statut', ['en_attente', 'en_cours', 'termine', 'annule'])->default('en_attente');
            $table->dateTime('date_debut')->nullable();
            $table->dateTime('date_fin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordre_fabrications');
    }
};
