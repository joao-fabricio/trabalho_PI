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
        Schema::create('curriculos', function (Blueprint $table) {
            $table->id('id_curriculo');
            $table->unsignedBigInteger('id_candidato');
            $table->foreign('id_candidato')->references('id_candidato')->on('candidatos')->onDelete('cascade');
            $table->text('formacao')->nullable();
            $table->text('experiencias')->nullable();
            $table->text('competencias')->nullable();
            $table->text('idiomas')->nullable();
            $table->text('resumo_profissional')->nullable();
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curriculos');
    }
};