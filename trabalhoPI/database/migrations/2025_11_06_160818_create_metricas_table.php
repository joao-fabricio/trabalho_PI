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
        Schema::create('metricas', function (Blueprint $table) {
            $table->id('id_metrica');
            $table->string('entidade');
            $table->string('tipo')->nullable();
            $table->decimal('valor', 10, 2)->default(0);
            $table->date('referencia')->nullable();
            $table->timestamp('data_registro')->useCurrent();
            $table->timestamps();
        });//precisa ajustar 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metricas');
    }
};
