<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestador extends Model
{
    use HasFactory;

    protected $table = 'prestadores';

    protected $primaryKey = 'id_prestador';

    protected $fillable = [
        'id_usuario',
        'especialidade',
        'descricao', 
        'preco_base', 
        'cidade',
        //se lembrar de por ativo ou inativo
    ];
}
