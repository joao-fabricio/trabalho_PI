<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    use HasFactory;

    protected $table = 'avaliacoes';

    protected $primaryKey = 'id_avaliacao';

    protected $fillable = [
        'id_usuario',
        'id_prestador',
        'nota',
        'comentario',
        'data_avaliacao',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function prestador()
    {
        return $this->belongsTo(Prestador::class, 'id_prestador', 'id_prestador');
    }
}
