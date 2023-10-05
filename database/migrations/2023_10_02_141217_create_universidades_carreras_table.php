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
        Schema::create('universidades_carreras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_universidad');
            $table->unsignedBigInteger('id_carrera');
            $table->foreign('id_universidad')->references('id')->on('universidades');
            $table->foreign('id_carrera')->references('id')->on('carreras');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('universidades_carreras');
    }
};
