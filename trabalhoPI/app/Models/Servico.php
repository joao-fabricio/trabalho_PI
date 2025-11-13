<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    use HasFactory;

    protected $table = 'servicos';  

    protected $primaryKey = 'id_servico';

    protected $fillable = [
        'id_prestador',
        'titulo',
        'descricao',
        'preco',
        'categoria',
        'localidade',
    ];

    public function prestador()
    {
        return $this->belongsTo(Prestador::class, 'id_prestador', 'id_prestador');
    }

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class, 'id_servico', 'id_servico');
    }
}
