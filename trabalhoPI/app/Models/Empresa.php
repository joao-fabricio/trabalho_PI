<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresas';

    protected $primaryKey = 'id_empresa';

    protected $fillable = [
        'id_usuario', 
        'razao_socia',
        'nome_fantasia',
        'endereco',
        'site',
    ];

    public function usuarios()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function vagas()
    {
        return $this->hasMany(Vaga::class, 'id_empresa', 'id_empresa');
    }

    public function metricas()
    {
        return $this->hasMany(Metrica::class, 'id_usuario', 'id_usuario');
    }
}
