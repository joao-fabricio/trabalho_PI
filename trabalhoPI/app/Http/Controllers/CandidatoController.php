<?php

namespace App\Http\Controllers;

use App\Models\Candidato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CandidatoController extends Controller
{
    /**
     * Lista todos os candidatos do sistema
     */
    public function index()
    {
        $candidatos = Candidato::where('id_usuario')->get();
        
        return view('candidatos.index', compact('candidatos'));
    }

    public function meusDados()
    {
        $candidato = Candidato::where('id_usuario', Auth::id())
            ->with('usuario', 'curriculo')
            ->firstOrFail();

            return view('candidatos.meus_dados', compact('candidato'));
    }

    /**
     * Formulário de criação de perfil.
     */
    public function create()
    {
        return view('candidatos.create');
    }

    /**
     * Salva um novo perfil de candidato.
     */
    public function store(Request $request)
    {
        $request->validate([
            'habilidades' => 'nullable|string',
            'experiencia' => 'nullable|string',
            'cidade' => 'nullable|string|max:150',
            'estado' => 'nullable|string|max:2',
        ]);
        Candidato::create([
            'id_usuario' => Auth::id(),
            'habilidades' => $request->habilidades,
            'experiencia' => $request->experiencia,
            'cidade' => $request->cidade,
            'estado' => $request->estado,
        ]);

        return redirect()->route('candidatos.meuPerfil')
        ->with('success', 'Perfil de candidato criado com sucesso.');
    }

    /**
     * Exibe o perfil completo de um candidato.
     */
    public function show($id)
    {
        $candidato = Candidato::whith(['usuario', 'curriculo'])->findOrFail($id);
        return view('candidatos.show', compact('candidato'));
    }

    /**
     * Formulário de edição de candidato logado.
     */
    public function edit($id_candidato)
    {
        $candidato = Candidato::findOrfail($id_candidato);
        
        if ($candidato->id_usuario !== Auth::id()) {
            abort(403, 'Acesso não autorizado.');
        }

        return view('candidatos.edit', compact('candidato'));
    }

    /**
     * Atualizar dados.
     */
    public function update(Request $request, $id_candidato)
    {
        $candidato = Candidato::findOrfail($id_candidato);

        if($candidato->id_usuario !== Auth::id()){
            abort(403, 'Acesso não autorizado.');
        }

        request()->validate([
            'habilidades' => 'nullable|string',
            'experiencia' => 'nullable|string',
            'cidade' => 'nullable|string|max:150',
            'estado' => 'nullable|string|max:2',
        ]);

        $candidato->update([
            'habilidades' => $request->habilidades,
            'experiencia' => $request->experiencia,
            'cidade' => $request->cidade,
            'estado' => $request->estado,
        ]);

        return redirect()->route('candidatos.meuPerfil')
            ->with('success', 'Perfil de candidato atualizado com sucesso.');
    }

    /**
     * Remove definitivamente.
     */
    public function destroy($id_candidato)
    {
        $candidato = Candidato::findOrfail($id_candidato);

        if ($candidato->id_usuario !== Auth::id() && Auth::user()->tipo !== 'admin'){
            abort(403, 'Acesso não autorizado.');
        }
        $candidato->delete();

        return redirect()->route('home')
            ->with('success', 'Perfil de candidato excluído com sucesso.');
    }
}
