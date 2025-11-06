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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('nome', 150);
            $table->string('email', 150)->unique();
            $table->string('senha', 255);
            $table->string('telefone', 20)->nullable();
            $table->enum('tipo', ['candidato', 'empresa', 'prestador', 'admin']);
            $table->timestamp('data_cadastro')->useCurrent();
            $table->boolean('ativo')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
