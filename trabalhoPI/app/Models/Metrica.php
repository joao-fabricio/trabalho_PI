<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metrica extends Model
{
    use HasFactory;

    protected $table = 'metricas';
    
    protected $primaryKey = 'id_metrica';

    protected $fillable = [
        'entidade',
        'tipo',
        'valor',
        'referencia',
        'data_registro',   
     ];

     // Garantia de tipos corretos ao manipular dados

    protected $casts = [
        'valor' => 'float',
        'referencia' => 'date',
        'data_registro' => 'datetime',
    ];
    // Escopo para filtrar por entidade
    public function scopeDaEntidade($query, $entidade)
    {
        return $query->where('entidade', $entidade);
    }

    // Escopo para filtrar por data de referência

    public function scopeDaReferencia($query, $data)
    {
        return $query->where('referencia', $data);
    }

    // retorna a última métrica registrada para uma entidade específica

    public static function ultima($entidade, $tipo = null)
    {
        $query = self::where('entidade', $entidade);
        if ($tipo) {
            $query->where('tipo', $tipo);
        }
        return $query->orderBy('data_registro')->first();

    }
}
