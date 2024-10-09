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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string("ref")->nullable();
            $table->string("nom_complet")->nullable();
            $table->string("nature")->nullable();
            $table->integer("montant")->default(0);
            $table->string("adresse")->nullable();
            $table->float("remise")->nullable();
            $table->unsignedBigInteger('medecin_id')->nullable();
            $table->foreign('medecin_id')->references('id')->on('medecins');
            $table->unsignedBigInteger('module_id')->nullable();
            $table->foreign('module_id')->references('id')->on('modules');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
