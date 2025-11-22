<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\PrestadorController;
use App\Http\Controllers\UsuarioController;
use App\Models\Prestador;
use Termwind\Components\Raw;

Route::get('/', function () {
    return view('welcome');
});

// LOGIN
Route::get('/login', [AuthController::class, 'formlogin'])->name('login'); //exibe o formulário de login
Route::post('/login', [AuthController::class, 'login']); //processa o login

// REGISTRO
Route::get('/register', [AuthController::class, 'formRegister'])->name('register'); //exibe o formulário de registro
Route::post('/register', [AuthController::class, 'register']); //processa o registro

// LOGOUT
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// DASHBOARD
Route::get('/dashboard', function(){
    return view('dashboard');
})->middleware('auth');

//ROTAS DE USUÁRIO
Route::prefix('usuarios')->middleware('auth')->name('usuarios.')->group(function(){
    Route::get('/', [UsuarioController::class, 'index'])->name('index'); //lista de usuários (admin)
    Route::get('/create', [UsuarioController::class, 'create'])->name('create'); //formulário de criação de usuário
    Route::post('/', [UsuarioController::class, 'store'])->name('store'); //salva novo usuário

    Route::get('/perfil', [UsuarioController::class, 'perfil'])->name('perfil'); //perfil do usuário

    Route::get('editar', [UsuarioController::class, 'edit'])->name('edit'); //formulário de edição de perfil
    Route::put('/atualizar', [UsuarioController::class, 'update'])->name('update'); //Atualizar perfil

    Route::delete('/{id_usuario}', [UsuarioController::class, 'destroy'])->name('destroy'); //deleta usuário (admin)

});

//ROTAS DE EMPRESA
Route::middleware('auth')->prefix('empresas.')->group(function () {

    // Empresas do usuário logado
    Route::get('/', [EmpresaController::class, 'index'])->name('index');

    // Listar todas (somente admin — coloque middleware depois)
    Route::get('todas', [EmpresaController::class, 'listarTodas'])->name('listarTodas');

    // Criação da empresa
    Route::get('create', [EmpresaController::class, 'create'])->name('create');

    Route::post('/', [EmpresaController::class, 'store'])->name('store');

    // Detalhes
    Route::get('/{id_empresa}', [EmpresaController::class, 'show'])->name('show');

    // Edição
    Route::get('/{id_empresa}/edit', [EmpresaController::class, 'edit'])->name('edit');

    Route::put('/{id_empresa}', [EmpresaController::class, 'update'])->name('update');

    // Excluir
    Route::delete('/{id_empresa}', [EmpresaController::class, 'destroy'])->name('destroy');

    //Olhar se os formulários de criação e edição estão ok
});

//ROTAS DE PRESTADOR
Route::middleware('auth')->group(function(){

    // Lista geral de prestador (qualquer usuário pode ver)
    Route::get('prestadores', [PrestadorController::class, 'index'])->name('prestadores.index');

    // Ver perfil de um prestador publico (com id)
    Route::get('/prestadores/{prestador}', [PrestadorController::class, 'show'])->name('prestadores.show');

    // Formulário de criação de prestador
    Route::get('me/prestador/criar', [PrestadorController::class, 'create'])->name('prestadores.create');

    // Salvar prestador
    Route::post('/me/prestador', [PrestadorController::class, 'store'])->name('prestadores.store');

    // Ver os dados do prestador logado
    Route::get('/me/prestador', [PrestadorController::class, 'meusDados'])->name('prestadores.meusDados');
    
    // Editar perfil de prestador logado
    Route::get('/me/prestador/editar', [PrestadorController::class, 'edit'])->name('prestadores.edit');

    // Atualizar dados do perfil de presyatador logado
    Route::put('/me/prestador', [PrestadorController::class, 'update'])->name('prestadores.update');
    
    // Excluir perfil de prestador logado
    Route::delete('/me/prestador', [PrestadorController::class, 'destroy'])->name('prestadores.destroy');
});

// ROTAS DE CANDIDATO
Route::prefix('candidatos')->middleware('auth')->name('candidatos.')->group(function(){

});