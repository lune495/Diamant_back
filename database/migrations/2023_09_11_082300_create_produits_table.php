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
        if (!Schema::hasTable('produits')) {
            Schema::create('produits', function (Blueprint $table) {
                $table->id();
                $table->string('code')->nullable();
                $table->string('designation')->nullable();
                $table->string('description')->nullable();
                $table->string('image')->nullable();
                $table->integer('pa')->nullable();
                $table->integer('pv')->nullable();
                $table->integer('limite')->nullable();
                $table->integer('qte')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
