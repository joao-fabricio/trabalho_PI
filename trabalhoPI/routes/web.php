<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidatoController;
use App\Http\Controllers\CandidaturaController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\PrestadorController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\VagaController;
use App\Models\Candidato;
use App\Models\Candidatura;
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

    // Lista todos os candidatos
    Route::get('/candidatos', [CandidatoController::class, 'index'])->name('candidatos.index');

    // Exibir meu perfil de candidato
    Route::get('/candidato/meu-perfil', [CandidatoController::class, 'meusDados'])->name('candidatos.meuPerfil');

    // Formulário de criação de candidato
    Route::get('/candidato/create', [CandidatoController::class, 'create'])->name('candidatos.create');

    // Salvar novo candidato
    Route::post('/candidato', [CandidatoController::class, 'store'])->name('candidatos.store');

    // Mostrar perfil de um candidato específico
    Route::get('/candidato/{id}', [CandidatoController::class, 'show'])->name('candidatos.show');

    // Formulário de edição (somente no próprio perfil, mas admin pode editar)
    Route::get('/candidato/{id}/edit', [CandidatoController::class, 'edit'])->name('candidatos.edit');
    
    // Atualizar perfil
    Route::put('/candidato/{id_candidato}', [CandidatoController::class, 'update'])->name('candidatos.update');

    // Excluir perfil de candidato
    Route::delete('/candidato/{id_candidato}', [CandidatoController::class, 'destroy'])->name('candidatos.destroy');
});

// ROTAS DE VAGAS
//rotas públicas
Route::get('/vagas', [VagaController::class, 'index'])->name('vagas.index');

Route::get('/vagas/{id_vaga}', [VagaController::class, 'show'])->name('vagas.show');

//rotas privadas pra empresa logada
Route::middleware(['auth'])->group(function(){
    
    //Listar vagas da empresa logada
    Route::get('/empresa/vagas', [VagaController::class, 'index'])->name('vagas,minhas');

    //Formulário de criar vaga
    Route::get('/empresa/vagas/criar', [VagaController::class, 'create'])->name('vagas.create');

    //Salvar vaga
    Route::post('/empresa/vagas', [VagaController::class, 'store'])->name('vagas.store');

    //Fprmulário de editar vaga
    Route::get('/empresa/vagas/{id_vaga}/editar', [VagaController::class, 'edit'])->name('vagas.edit');

    //Atualizar vaga
    Route::put('/empresa/vagas/{id_vaga}', [VagaController::class, 'update'])->name('vagas.edit');

    //Deletar vaga
    Route::delete('/empresa/vagas/{id_vaga}', [VagaController::class, 'destroy'])->name('vagas.destroy');
});


// ROTAS DE SERVIÇOS
Route::get('/servicos', [ServicoController::class, 'indexAll'])->name('servicos.listar');

Route::middleware(['auth', 'prestador'])->group(function(){

    // Lista serviços do prestador logado
    Route::get('/meus-servicos', [ServicoController::class, 'index'])->name('servicos.create');

    // Criar serviço
    Route::get('/servicos/create', [ServicoController::class, 'create'])->name('servicos.create');

    Route::post('/servivos', [ServicoController::class, 'store'])->name('servico.store');

    // Editar serviço
    Route::get('/servicos/{id_servico}/edit', [ServicoController::class, 'edit'])->name('servico.edit');

    // Atualizar serviço
    Route::put('/servicos/{id_servico}', [ServicoController::class, 'update'])->name('servicos.update');

    //Excluir serviço
    Route::delete('/servicos/{id_servico}', [ServicoController::class, 'destroy'])->name('servicos.destroy');

    // se ligar em fazer os middleware restantes se der tempo
});
// ROTAS DE CANDIDATURAS
Route::middleware(['auth'])->group(function(){

    //Candidatos se innscrevem na vaga
    Route::post('/vagas/{id}/candidatar', [CandidaturaController::class, 'candidatar'])->name('candidaturas.candidatar');

    //ver minhas candidaturas
    Route::get('/minhas-candidaturas', [CandidaturaController::class, 'minhas'])->name('candidaturas.minhas');

    //cancelar candidatura
    Route::delete('/candidaturas/{id}', [CandidaturaController::class, 'cancelar'])->name('candidaturas.cancelar');
});

// Apenas empresa pode gerenciar candidaturas   (middleware diferente)

Route::middleware(['auth'])->group(function(){
    //ver candidaturas de uma vaga
    Route::get('/vagas/{id}/candidaturas', [CandidaturaController::class, 'listarPorVaga'])->name('canmdidaturas.porVagas');
    
    //aceitar candidatura
    Route::put('/candidatura/{id}/aceitar', [CandidaturaController::class, 'aceitar'])->name('candidaturas.aceitar');

    //rejeitar candidatura
    Route::put('candidatura/{id}/rejeitar', [CandidaturaController::class, 'rejeitar'])->name('candidaturas.rejeitar');
});
// ROTAS DE AGENDAMENTOS

Route::middleware(['auth'])->group(function(){

});
// ROTAS DE CURRÍCULOS

Route::middleware(['auth'])->group(function(){

});
// ROTAS DE AVALIAÇÕES

Route::middleware(['auth'])->group(function(){

});
// ROTAS DE ADMIN (AINDA EM AJUSTES)
Route::middleware(['auth'])->group(function(){

});