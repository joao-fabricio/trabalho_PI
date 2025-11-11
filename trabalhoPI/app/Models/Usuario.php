<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';

    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'nome',
        'email', 
        'senha',
        'telefone', 
        'tipo',
        'ativo',
    ];

    public function empresa()
    {
        return $this->hasOne(Empresa::class, 'id_usuario', 'id_usuario');
    }

    public function candidato()
    {
        return $this->hasOne(Candidato::class, 'id_usuario', 'id_usuario');
    }
    public function vagasCandidatadas()
    {
        return $this->belongsToMany(Vaga::class, 'candidaturas', 'id_candidato', 'id_vaga')
                    ->withPivot('data_candidatura', 'status');
    }

    public function agendamentos()
    {
        return $this->belongsToMany(Servico::class, 'agendamentos', 'id_usuario', 'id_servico')
        ->withPivot('data_agendada', 'status', 'observacoes');
    }

    public function avaliacoesFeitas()
    {
        return $this->hasMany(Avaliacao::class, 'id_usuario', 'id_usuario');
    }

    public function avaliacoesRecebidas()
    {
        return $this->hasMany(Avaliacao::class, 'id_prestador', 'id_usuario');
    }
    
    public function metricas()
    {
        return $this->hasMany(Metrica::class, 'id_usuario', 'id_usuario');
    }
    
    public function curriculo()
    {
        return $this->hasOne(Curriculo::class, 'id_candidato', 'id_usuario');
    }
    
}