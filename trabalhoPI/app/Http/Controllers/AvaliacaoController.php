<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use Illuminate\Http\Request;

class AvaliacaoController extends Controller
{
    /**
     * Listar todas as avaliações.
     */
    public function index()
    {
        $avaliacoes = Avaliacao::with(['usuario', 'servico'])->get();

        return response()->json($avaliacoes, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Criar nova avaliação.
     */
    public function store(Request $request)
    {
        // Valida os dados antes de salvar
        $validated = $request->validate([
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'id_prestador' => 'required|exists:prestadores,is_prestador',
            'nota' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:1000',
        ]);

        //observar se é comentario msm

        $avaliacao = Avaliacao::create($validated);

        return response()->json([
            'message' => 'Avaliação registrada com sucesso',
            'avaliacao' => $avaliacao
        ], 201);
    }

    /**
     * Mostrar avaliação específica.
     */
    public function show($id)
    {
        $avaliacao = Avaliacao::with(['usuario', 'prestador'])->findOrFail($id);

        return response()->json($avaliacao, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Avaliacao $avaliacao)
    {
        //
    }

    /**
     * Atualizar avaliação.
     */
    public function update(Request $request, $id)
    {
        $avaliacao = Avaliacao::findOrFail($id);

        $validated = $request->validate([
            'nota' => 'nullable|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:1000',
        ]);

        $avaliacao->update($validated);

        return response()->json([
            'message' => 'Avaliação atualizada com sucesso',
            'avaliacao' => $avaliacao
        ], 200);
    }

    /**
     * Excluir avaliação.
     */
    public function destroy($id)
    {
        $avaliacao = Avaliacao::findOrFail($id);

        $avaliacao->delete();

        return response()->json([
            'message' => 'Avaliação removida com sucesso'
        ], 200);
    }
    //padronizar mensagens de exclusão e do json
}
