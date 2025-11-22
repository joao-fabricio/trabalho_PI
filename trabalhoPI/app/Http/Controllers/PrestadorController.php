<?php

namespace App\Http\Controllers;

use App\Models\Prestador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrestadorController extends Controller
{
    /**
     * Listar todos os prestadores (para quem for contratar).
     */
    public function index()
    {
        //apenas com usuário ativo
        $prestadores = Prestador::with('usuario')->get();

        //pesquisar paginate10 pra por no lugar de get
        return view('prestadores.index', compact('prestadores'));
    }

    /**
     * Mostra o formulário pro usuário virar prestador.
     */
    public function create()
    {
        return view ('prestadores.create');
    }

    /**
     * Salvar o prestador no banco.
     */
    public function store(Request $request)
    {
        $request->validate([
            'especialidade' => 'required|string|max:100',
            'descricao' => 'nullable|string',
            'preco_base' => 'required|numeric|min:0',
            'cidade' => 'required|string|max:100',
        ]);

        Prestador::create([
            'id_usuario' => Auth::id(),
            'especialidade' => $request->especialidade,
            'descricao' => $request->descricao,
            'preco_base' => $request->preco_base,
            'cidade' => $request->cidade,
        ]);
        
        return redirect()->route('prestadores.index')->with('success', 'Você agora é um prestador de serviços!');
    }

    public function meusDados()
    {
        $prestador = Prestador::where('id_usuario', Auth::id())->firstOrFail();
        return view('prestadores.meus_dados', compact('prestador'));
    }

    /**
     * Mostra um perfil de um prestador específico.
     */
    public function show(Prestador $prestador)
    {
        $prestador->load(['usuario', 'avaliacoes', 'agendamentos']);
        return view('prestadores.show', compact('prestador'));
    }

    /**
     * Formulário de edição do prestador logado.
     */
    public function edit($id_prestador)
    {
        $prestador = Prestador::where('id_usuario', Auth::id())->firstOrFail();
        return view('prestadores.edit', compact('prestador'));
    }

    /**
     * Atualizar dados.
     */
    public function update(Request $request)
    {
        $prestador  = Prestador::where('id_usuario', Auth::id())->firstOrFail();

        $request->validate([
            'especialidade' => 'required|string|max:100',
            'descricao' => 'nullable|string',
            'preco_base' => 'required|numeric|min:0',
            'cidade' => 'required|string|max:10',
        ]);

        $prestador->update([
            'especialidade' => $request->especialidade,
            'descricao' => $request->descricao,
            'preco_base' => $request->preco_base,
            'cidade' => $request->cidade,
        ]);

        return redirect()->route('prestadores.meusDados')->with('success', 'Seus dados foram atualizados com sucesso.');
    }

    /**
     * Apagar perfil.
     */
    public function destroy()
    {
        $prestador = Prestador::where('id_usuario', Auth::id())->firstOrFail();
        
        $prestador->delete();

        return redirect()->route('home')->with('success', 'Seu perfil de prestador foi removido.');
    }
}
