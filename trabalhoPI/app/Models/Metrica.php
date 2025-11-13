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
        'tipo',
        'valor',   
     ];
}
