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
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['Semis', 'Arrosage', 'Fertilisation', 'Recolte', 'Traitement']);
            $table->boolean('isDone')->default(false);
            
            $table->float('quantity')->nullable(); // Quantité utilisée (ex: 50 kg)
            $table->string('unit')->nullable();    // Unité (ex: kg, L, m3)
            $table->string('product_name')->nullable(); // Produit utilisé (ex: semence de blé, engrais NPK, pesticide XYZ)

            $table->text('description');
            $table->foreignId('land_id')->constrained('lands')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};
