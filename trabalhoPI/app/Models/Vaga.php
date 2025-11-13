<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaga extends Model
{
    use HasFactory;

    protected $table = 'vagas';

    protected $primaryKey = 'id_vaga';

    protected $fillable = [
        'id_empresa',
        'titulo',
        'descricao',
        'requisitos',
        'salario',
        'localidade',
        'status',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id_empresa');
    }

    public function candidaturas()
    {
        return $this->hasMany(Candidatura::class, 'id_vaga', 'id_vaga');
    }
}
