<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpresaController extends Controller
{
    /**
     * Listar empresas (somente usuário logado).
     */
    public function index()
    {
        $empresas = Empresa::where('id_usuario', Auth::id())->get(); return view('empresas.index', compact('empresas'));
    }

    /**
     * Formulário de criação.
     */
    public function create()
    {
        return view('empresas.create');
    }

    /**
     * Salvar nova empresa.
     */
    public function store(Request $request)
    {
        $request->validate([
            'razao_social' => 'required|string|max:150',
            'nome_fantasia' => 'required|string|max:150',
            'endereco' => 'nullable|string|max:255',
            'site' => 'nullable|url|max:150',
        ]);

        Empresa::create([
            'id_usuario' => Auth::id(),
            'razao_social' => $request->razao_social,
            'nome_fantasia' => $request->nome_fantasia,
            'endereco' => $request->endereco,
            'site' => $request->site,
        ]);
        
        return redirect()->route('empresas.index')->with('success', 'Empresa criada com sucesso.');
    }

    /**
     * Exibir uma empresa.
     */
    public function show(Empresa $empresa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empresa $empresa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empresa $empresa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empresa $empresa)
    {
        //
    }
}
