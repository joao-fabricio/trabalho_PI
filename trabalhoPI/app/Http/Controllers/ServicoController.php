<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServicoController extends Controller
{
    /**
     * Lista serviços do prestador.
     */
    public function index()
    {
        $servicos = Servico::where('id_prestador', Auth::user()->prestador->id_prestador)->get();
        return view('servicos.index', compact('servicos'));
    }

     /**
     * Lista todos os serviços (todos podem ver).
     */
    public function indexAll()
    {
        $servicos = Servico::all();
        return view('servicos.listar', compact('servicos'));
    }

    /**
     * Formulário de criação.
     */
    public function create()
    {
        return view('servicos.create');
    }

    /**
     * Salva novo serviço.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'preco' => 'required|numeric|min:0',
            'categoria' => 'required|string|max:255',
            'localidade' => 'nullable|string|max:255',
        ]);

        Servico::create([
            'id_prestador' => Auth::user()->prestador->id_prestador,
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'preco' => $request->preco,
            'categoria' => $request->categoria,
            'localidade' => $request->localidade,
        ]);

        return redirect()->route('servicos.index')->with('success', 'Serviço criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Servico $servico)
    {
        //
    }

    /**
     * Formulário de edição.
     */
    public function edit(Servico $servico)
    {
        $servico = Servico::findOrFail($servico->id_servico);

        // impedir edição de serviços de outros prestadores
        if ($servico->id_prestador !== Auth::user()->prestador->id_prestador) {
            return redirect()->route('servicos.index')->with('error', 'Acesso negado.');
        }
        return view('servicos.edit', compact('servico'));
    }

    /**
     * Atualizar serviço.
     */
    public function update(Request $request, $id_servico)
    {
        $servico = Servico::findOrFail($id_servico);

        // impedir edição de serviços de outros prestadores
        if ($servico->id_prestador !== Auth::user()->prestador->id_prestador) {
            return redirect()->route('servicos.index')->with('error', 'Acesso negado.');
        }

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'preco' => 'required|numeric|min:0',
            'categoria' => 'required|string|max:255',
            'localidade' => 'nullable|string|max:255',
        ]);

        $servico->update([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'preco' => $request->preco,
            'categoria' => $request->categoria,
            'localidade' => $request->localidade,
        ]);

        return redirect()->route('servicos.index')->with('success', 'Serviço atualizado com sucesso.');
    }

    /**
     * Excluir serviço.
     */
    public function destroy($id_servico)
    {
        $servico = Servico::findOrFail($id_servico);

        // impedir exclusão de serviços de outros prestadores
        if ($servico->id_prestador !== Auth::user()->prestador->id_prestador) {
            return redirect()->route('servicos.index')->with('error', 'Acesso negado.');
        }

        $servico->delete();

        return redirect()->route('servicos.index')->with('success', 'Serviço excluído com sucesso.');
    }
}
