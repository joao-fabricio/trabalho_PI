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
        Schema::create('vagas', function (Blueprint $table) {
            $table->id('id_vaga');
            $table->unsignedBigInteger('id_empresa');
            $table->foreign('id_empresa')->references('id_empresa')->on('empresas')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descricao');
            $table->text('requisitos')->nullable();
            $table->decimal('salario', 10, 2)->nullable();
            $table->string('localidade')->nullable();
            $table->timestamp('data_publicacao')->useCurrent();
            $table->enum('status', ['aberta', 'fechada'])->default('aberta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vagas');
    }
};
