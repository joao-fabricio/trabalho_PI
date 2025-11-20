<?php

namespace App\Http\Controllers;

use App\Models\Vaga;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VagaController extends Controller
{
    /**
     * Listar todas as vagas para os usuários.
     */
    public function index()
    {
        $vagas = Vaga::with('empresa')
            ->where('status', 'aberta')
            ->orderBy('created_at', 'desc')
            ->get();
            //confirmar se é aberta msm
        return view('vagas.index', compact('vagas'));    
    }

    /**
     * Formulário de criação de vaga.
     */
    public function create()
    {
        $empresa = Empresa::where('id_usuario', Auth::id())->firstOrFail();

        return view('vagas.create', compact('empresa'));
    }

    /**
     * Armazenar nova vaga.
     */
    public function store(Request $request)
    {
        $empresa = Empresa::where('id_usuario', Auth::id())->firstOrFail();

        $request->validate([
            'titulo' => 'required|string|max:150',
            'descricao' => 'required|string',
            'requisitos' => 'nullable|string',
            'salario' => 'nullable|numeric',
            'localidade' => 'nullable|string|max:150',
        ]);

        Vaga::create([
            'id_empresa' => $empresa->id,
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'requisitos' => $request->requisitos,
            'salario' => $request->salario,
            'localidade' => $request->localidade,
            'status' => 'aberta',
        ]);

        return redirect()->route('vagas.minhas')
            ->with('success', 'Vaga criada com sucesso.');

    }

    /**
     * Detalhe de uma vaga.
     */
    public function show($id_vaga)
    {
        $vaga = Vaga::with('empresa')->findOrFail($id_vaga);

        return view('vagas.show', compact('vaga'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_vaga)
    {
        $empresa = Empresa::where('id_usuario', Auth::id())->firstOrFail();

        $vaga = Vaga::where('id_empresa', $empresa->id)
            ->findOrFail($id_vaga);

        return view('vagas.edit', compact('vaga'));    
    }

    /**
     * Atualizar vaga.
     */
    public function update(Request $request, $id_vaga)
    {
        $empresa = Empresa::where('id_usuario', Auth::id())->firstOrFail();

        $vaga = Vaga::where('id_empresa', $empresa->id)
            ->findOrFail($id_vaga);

        $request->validate([
            'titulo' => 'required|string|max:150',
            'descricao' => 'required|string',
            'requisitos' => 'nullable|string',
            'salario' => 'nullable|numeric',
            'localidade' => 'nullable|string|max:150',
            'status' => 'required|in:aberta,fechada',
        ]);    

        //estudar esse in:aberta,fechada

        $vaga->update($request->all());

        return redirect()->route('vagas.minhas')
            ->with('success', 'Vaga atualizada com sucesso.');
    }

    /**
     * Excluir vaga (cascade remove candidaturas).
     */
    public function destroy($id_vaga)
    {
        $empresa = Empresa::where('id_usuario', Auth::id())->firstOrFail();

        $vaga = Vaga::where('id_empresa', $empresa->id)
            ->findOrFail($id_vaga);

        $vaga->delete();

        return redirect()->route('vagas.minhas')
            ->with('success', 'Vaga excluída com sucesso.');
    }
}
