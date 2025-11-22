<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
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

    public function getAuthPassword()
    {
        return $this->senha;
    }
    //colocar admin

    public function empresa()
    {
        return $this->hasOne(Empresa::class, 'id_usuario', 'id_usuario');
    }

    public function prestador()
    {
        return $this->hasOne(Prestador::class, 'id_usuario', 'id_usuario');
    }

    public function candidato()
    {
        return $this->hasOne(Candidato::class, 'id_usuario', 'id_usuario');
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

    public function admins()
    {
        return $this->hasOne(Admin::class, 'id_usuario', 'id_usuario');
    }
}
//ajustes aqui