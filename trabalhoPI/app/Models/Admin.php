<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;

class Admin extends Model
{
    protected $table = 'admins';
    protected $primaryKey = 'id_admin';

    protected $fillable = [
        'id_usuario',
        'cargo',
        'ultimo_login',
        'ip_ultimo_login',
        'login_count',
    ];
    //estudar essa parte de ip e login count
    protected $casts = [
        'ultimo_login' =>'datetime',
    ];
    //estudar essa parte

    // Um admin pertence a um usuário
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
