<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidatura extends Model
{
    use HasFactory;

    protected $table = 'candidaturas';

    protected $primaryKey = 'id_candidatura';

    protected $fillable = [
        'id_candidato',
        'id_vaga',
        'status',
    ];

    public function candidato()
    {
        return $this->belongsTo(Candidato::class, 'id_candidato', 'id_candidato');
    }

    public function vaga()
    {
        return $this->belongsTo(Vaga::class, 'id_vaga', 'id_vaga');
    }
}
