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
        Schema::create('candidaturas', function (Blueprint $table) {
            $table->id('id_candidatura');
            $table->unsignedBigInteger('id_candidato');
            $table->foreign('id_candidato')->references('id_candidato')->on('candidatos')->onDelete('cascade');
            $table->unsignedBigInteger('id_vaga');
            $table->foreign('id_vaga')->references('id_vaga')->on('vagas')->onDelete('cascade');
            $table->timestamp('data_candidatura')->useCurrent();
            $table->enum('status', ['pendente', 'aceita', 'rejeitada'])->default('pendente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidaturas');
    }
};
