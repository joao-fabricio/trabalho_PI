<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestador extends Model
{
    use HasFactory;

    protected $table = 'prestadores';

    protected $primaryKey = 'id_prestador';

    protected $fillable = [
        'id_usuario',
        'especialidade',
        'descricao', 
        'preco_base', 
        'cidade',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function servicos()
    {
        return $this->hasMany(Metrica::class, 'id_prestador', 'id_prestador');
    }

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class, 'id_prestador', 'id_prestador');
    }

    public function avaliacoes()
    {
        return $this->hasMany(Avaliacao::class, 'id_prestador', 'id_prestador');
    }

    public function metricas()
    {
        return $this->hasMany(Metrica::class, 'entidade', 'id_prestador');
    }
}
