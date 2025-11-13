<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidato extends Model
{
    use HasFactory;

    protected $table = 'candidatos';

    protected $primaryKey = 'id_candidato';

    protected $fillable = [
        'id_usuario',
        'habilidades',
        'experiencia',
        'cidade',
        'estado',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function curriculo()
    {
        return $this->hasMany(Curriculo::class, 'id_candidato', 'id_candidato');
    }

    public function candidaturas()
    {
        return $this->hasMany(Candidatura::class, 'id_candidato', 'id_candidato');
    }
}
