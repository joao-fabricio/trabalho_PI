<?php

namespace App\Http\Controllers;

use App\Models\Candidatura;
use Illuminate\Http\Request;

class CandidaturaController extends Controller
{
    /**
     * Lista todas as candidaturas.
     */
    public function index()
    {
        $candidaturas = Candidatura::with(['candidato', 'vaga'])->get();

        return response()->json($candidaturas, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Criar nova candidatura.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_candidato' => 'required|exists:candidatos,id_candidato',
            'id_vaga' => 'required|exists:vagas,id_vaga',
        ]);

        // verificar se o candidato já se candidatou à vaga
        $jaExiste = Candidatura::where('id_candidato', $request->id_candidato)
            ->where('id_vaga', $request->id_vaga)
            ->first();

        if ($jaExiste) {
            return response()->json([
                'message' => 'Candidato já se candidatou a esta vaga.'
            ], 409);
        }    

        $candidatura = Candidatura::create([
            'id_candidato' => $request->id_candidato,
            'id_vaga' => $request->id_vaga,
            'status' => 'pendente',
        ]);

        return response()->json([
            'message' => 'Candidatura enviada com sucesso',
            'candidatura' => $candidatura
        ], 201);
    }

    /**
     * Mostrar candidatura específica.
     */
    public function show($id)
    {
        $candidatura = Candidatura::with(['candidato', 'vaga'])->findOrFail($id);

        return response()->json($candidatura, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Candidatura $candidatura)
    {
        //
    }

    /**
     * Atualiza status da candidatura.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pendente,aceito,rejeitado',
        ]);

        $candidatura = Candidatura::findOrFail($id);

        $candidatura->update([
            'status' => $request['status']
        ]);

        return response()->json([
            'message' => 'Status da candidatura atualizada com sucesso',
            'candidatura' => $candidatura
        ], 200);
    }

    /**
     * Exluir candidatura.
     */
    public function destroy($id)
    {
        $candidatura = Candidatura::findOrFail($id);

        $candidatura->delete();

        return response()->json([
            'message' => 'Candidatura removida com sucesso'
        ], 200);
    }
}
