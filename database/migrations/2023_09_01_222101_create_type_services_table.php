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
        Schema::create('type_services', function (Blueprint $table) {
            $table->id();
            $table->string("nom");
            $table->integer("prix");
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
        Schema::dropIfExists('type_services');
    }
};
