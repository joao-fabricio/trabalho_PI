<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    use HasFactory;

    protected $table = 'agendamentos';

    protected $primaryKey = 'id_agendamento';

    protected $fillable = [
        'id_usuario',
        'id_servico',
        'data_agendamento',
        'observacoes',
        'status',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function prestador()
    {
        return $this->belongsTo(Prestador::class, 'id_prestador', 'id_prestador');
    }

    public function servico()
    {
        return $this->belongsTo(Servico::class, 'id_servico', 'id_servico');
    }
}
