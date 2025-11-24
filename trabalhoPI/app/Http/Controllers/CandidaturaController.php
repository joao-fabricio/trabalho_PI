<?php

namespace App\Http\Controllers;

use App\Models\Candidatura;
use App\Models\Vaga;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
    //candidaturas da empresa logada

    public function minhas()
        {
        $candidato = Auth::user()->candidato;

        if (!$candidato){
            abort(403, 'Somente candidatos podem acessar');
        }
        $candidaturas = Candidatura::with('vaga')
            ->where('id_candidato', $candidato->id_candidato)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('candidaturas.minhas', compact('candidaturas'));
    }

    //candidaturas recebidas pela empresa do usuário logado
    public function recebidas()
    {
        $empresa = Empresa::where('id_usuario', Auth::id())->firstOrFail();

        // vagas dessa empresa
        $candidaturas = Candidatura::with(['candidato', 'vaga'])
            ->whereHas('vaga', function ($q) use ($empresa) {
                $q->where('id_empresa', $empresa->id_empresa);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('candidaturas.recebidas', compact('candidaturas'));        
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
    public function store(Request $request, $id_vaga)
    {
        $candidato = Auth::user()->candidato;

        if (!$candidato){
            abort(403, 'Somente candidatos podem se candidatar');
        }
        
        //garantir se existe a vaga
        $vaga = Vaga::findOrFail($id_vaga);

        /*
        $validated = $request->validate([
            'id_vaga' => 'required|exists:vagas,id_vaga',
        ]);
        **/

        // verificar se o candidato já se candidatou à vaga
        $jaExiste = Candidatura::where('id_candidato', $request->id_candidato)
            ->where('id_vaga', $id_vaga)
            ->first();

        if ($jaExiste) {
            return back()->with('error', 'Você já se candidatou para esta vaga.');
        }    

        Candidatura::create([
            'id_candidato' => $candidato->id_candidato,
            'id_vaga' => $request->id_vaga,
            'status' => 'pendente',
        ]);

        return back()->with('sucess', 'Candidatura enviada com sucesso.');
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
        $request->validate([
            'status' => 'required|in:pendente,aceita,rejeitada',
        ]);

        $empresa = Empresa::where('id_usuario', Auth::id())->firstOrFail();

        $candidatura = Candidatura::with('vaga')->findOrFail($id);

        // empresa so muda candidatura de suas vagas
        if($candidatura->vaga->id_empresa != $empresa->id_empresa) {
            abort(403, 'Você não pode alterar essa candidatura');
        }
        $candidatura->update([
            'status' => $request->status,
        ]);

        return back()->with('sucess', 'Status atualizado com sucesso.');
    }

    /**
     * Exluir candidatura.
     */
    public function destroy($id)
    {
        $candidatura = Candidatura::with('vaga')->findOrFail($id);
        $user = Auth::user();

        //estudar aqui pra baixo

        $isCandidato = $user->candidato && $user->candidato->id_candidato == $candidatura->id_candidato;

        $empresa = Empresa::where('id_usuario', Auth::id())->first();
        $isEmpresa = $empresa && $candidatura->vaga->id_empresa == $empresa->id_empresa;

        if (!$isCandidato && !$isEmpresa) {
            abort(403, 'Você não tem permissão para apagar esta candidatura.');
        }

        $candidatura->delete();

        return response()->json([
            'message' => 'Candidatura removida com sucesso'
        ], 200);
    }
}
