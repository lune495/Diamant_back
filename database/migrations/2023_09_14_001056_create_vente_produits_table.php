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
        if (!Schema::hasTable('vente_produits')) {
            Schema::create('vente_produits', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('produit_id');
                $table->foreign('produit_id')->references('id')->on('produits');
                $table->unsignedBigInteger('vente_id');
                $table->foreign('vente_id')->references('id')->on('ventes');
                $table->integer('qte')->default('0');
                $table->float('remise')->nullable()->default('0');
                $table->integer('prix_vente');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vente_produits');
    }
};
