<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpresaController extends Controller
{
    /**
     * Listar empresas (somente usuário logado)
     * dashboard de minhas empresas.
     */
    public function index()
    {
        $empresas = Empresa::where('id_usuario', Auth::id())->get();

        return view('empresas.index', compact('empresas'));
    }

    public function listarTodas()
    {
        $empresas = Empresa::with('usuario')->get();

        return view('empresas.lista', compact('empresas'));
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
     * Exibir detalhes de uma empresa.
     */
    public function show($id_empresa)
    {
        $empresa = Empresa::where('id_empresa', $id_empresa)
            ->where('id_usuario', Auth::id())
            ->firstOrFail();

        return view('empresas.show', compact('empresa'));
    }

    /**
     * Formulário de edição.
     */
    public function edit($id_empresa)
    {
        $empresa = Empresa::where('id_empresa', $id_empresa)
            ->where('id_usuario', Auth::id())
            ->firstOrFail();    

        return view('empresas.edit', compact('empresa'));    
    }

    /**
     * Atualizar empresa.
     */
    public function update(Request $request, $id_empresa)
    {
        $request->validate([
            'razao_social' => 'required|string|max:150',
            'nome_fantasia' => 'required|string|max:150',
            'endereco' => 'nullable|string|max:255',
            'site' => 'nullable|url|max:150',
        ]);

        $empresa = Empresa::where('id_empresa', $id_empresa)
            ->where('id_usuario', Auth::id())
            ->firstOrFail();

        $empresa->update([
            'razao_social' => $request->razao_social,
            'nome_fantasia' => $request->nome_fantasia,
            'endereco' => $request->endereco,   
            'site' => $request->site,
        ]);
        
        return redirect()->route('empresas.index')->with('success', 'Empresa atualizada com sucesso.');
    }

    /**
     * Excluir.
     */
    public function destroy($id_empresa)
    {
        $empresa = Empresa::findOrFail($id_empresa);

        //APENAS DONO OU ADMIN PODEM DELETAR
        if(Auth::id() != $empresa->id_usuario && Auth::user()->tipo !== 'admin'){
            return response()->json(['error' => 'Ação não autorizada.'], 403);
        }

        //Exclui no banco definitivamente
        $empresa->delete();

        return response()->json(['success' => 'Empresa excluída com sucesso.'], 200);
    }
}
