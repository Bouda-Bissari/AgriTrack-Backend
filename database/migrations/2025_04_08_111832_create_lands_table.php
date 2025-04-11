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
        Schema::create('lands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->string('city');
            $table->string('cultureType');
            $table->double('area');
            $table->string('ownershipdoc');
            $table->double('latitude');
            $table->double('longitude');
            $table->enum('statut', ['En culture', 'Récolte', 'En jachère']);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lands');
    }
};
