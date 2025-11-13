<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculo extends Model
{
    use HasFactory;

    protected $table = 'curriculos';

    protected $primaryKey = 'id_curriculo';

    protected $fillable = [
        'id_candidato',
        'formacao',
        'experiencias',
        'competencias',
        'idiomas',
        'resumo_profissional',   
    ];

     public function candidato()
    {
        return $this->belongsTo(Candidato::class, 'id_candidato', 'id_candidato');
    }
}
