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
        if (!Schema::hasTable('ventes')) {
            Schema::create('ventes', function (Blueprint $table) {
                $table->id();
                $table->string('numero')->nullable()->default('FARMA0');
                $table->string('nom_complet')->nullable();
                $table->integer('montant')->nullable();
                $table->integer('qte')->default('0');
                $table->integer('montantencaisse')->nullable()->default('0');
                $table->integer('monnaie')->nullable()->default('0');
                $table->float('remise')->nullable()->default('0');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->foreign('user_id')->references('id')->on('users');
                $table->unsignedBigInteger('client_id')->nullable();
                $table->foreign('client_id')->references('id')->on('clients');
                $table->unsignedBigInteger('taxe_id')->nullable();
                $table->foreign('taxe_id')->references('id')->on('taxes');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
};
