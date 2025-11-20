<?php

namespace App\Http\Controllers;

use App\Models\Curriculo;
use App\Models\Candidato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurriculoController extends Controller
{
    /**
     * Lista o currículo do candidato logado.
     */
    public function index()
    {
        $candidato = Candidato::where('id_usuario', Auth::id())->first();

        if (!$candidato) {
            return response()->json(['message' => 'Você não possui cadastro de candidato.'], 404);
        }

        return Curriculo::where('id_candidato', $candidato->id_candidato)->first();

        return response()->json($curriculo);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Criar novo currículo.
     */
    public function store(Request $request)
    {
        $request->validate([
            'formacao' => 'nullable|string',
            'experiencias' => 'nullable|string',
            'competencias' => 'nullable|string',
            'idiomas' => 'nullable|string',
            'resumo_profissional' => 'nullable|string',
        ]);

        $candidato = Candidato::where('id_usuario', Auth::id())->first();

        if (!$candidato) {
            return response()->json(['message' => 'Você não é um candidato cadastrado.'], 404);
        }

        //caso o candidato já possua um currículo, bloqueia a criação de um novo
        if (Curriculo::where('id_candidato', $candidato->id_candidato)->exists()) {
            return response()->json(['message' => 'Você já possui um currículo.'], 400);
        }

        $curriculo = Curriculo::create([
            'id_candidato' => $candidato->id_candidato,
            'formacao' => $request->formacao,
            'experiencias' => $request->experiencias,
            'competencias' => $request->competencias,
            'idiomas' => $request->idiomas,
            'resumo_profissional' => $request->resumo_profissional,
        ]);

        return response()->json(['message' => 'Currículo criado com sucesso.', 'data' => $curriculo]);
    }

    /**
     * Mostra detalhes de um currículo específico.
     */
    public function show($id)
    {
        $curriculo = Curriculo::findOrFail($id);

        return response()->json($curriculo);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Curriculo $curriculo)
    {
        //
    }

    /**
     * Atualiza currículo do candidato.
     */
    public function update(Request $request, $id)
    {
        $curriculo = Curriculo::findOrFail($id);

        // Apenas o dono do currículo pode atualizá-lo
        if ($curriculo->candidato->id_usuario !== Auth::id()) {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }

        $curriculo->update($request->all());

        return response()->json(['message' => 'Currículo atualizado com sucesso.', 'data' => $curriculo]);
    }

    /**
     * Excluir currículo.
     */
    public function destroy($id)
    {
        $curriculo = Curriculo::findOrFail($id);

        // Apenas o dono pode excluir o currículo
        if ($curriculo->candidato->id_usuario !== Auth::id()) {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }

        $curriculo->delete();

        return response()->json(['message' => 'Currículo excluído com sucesso.']);
    }    
}
