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
        Schema::create('candidatures', function (Blueprint $table) {
            $table->id();
            $table->enum('statut', ['En Attente', 'Rejeté', 'Accepté']);
            $table->json('skills');
            $table->text('motivationLetter');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // worker
            $table->foreignId('intervention_id')->constrained('interventions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidatures');
    }
};
