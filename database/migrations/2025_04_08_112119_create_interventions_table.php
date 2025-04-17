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
            $table->float('productQuantity');
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
