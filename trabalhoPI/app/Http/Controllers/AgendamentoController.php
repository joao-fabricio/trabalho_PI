<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendamentoController extends Controller
{
    /**
     * Listar agendamentos do usuário logado (pode ser cliente ou prestador).
     */
    public function index()
    {
        $user = Auth::user();

        // Se for prestador busca agendamentos dos seus serviços dele
        if($user->tipo ==='prestador'){
            $agendamentos = Agendamento::whereHas('servico', function ($query) use ($user) {
                $query->where('id_prestador', $user->prestador->id_prestador);
            })->get();
        } else {
            // Cliente lista apenas seus próprios agendamentos
            $agendamentos = Agendamento::where('id_usuario', $user->id_usuario)->get();
        }

        return view('agendamentos.index', compact('agendamentos'));
    }

    /**
     * Formulário para criar novo agendamento.
     */
    public function create($id_servico)
    {
        $servico = Servico::findOrFail($id_servico);
        return view('agendamentos.create', compact('servico'));
    }

    /**
     * Salvar agendamento (cliente).
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_servico' => 'required|exists:servicos,id_servico',
            'data_agendada' => 'required|date|after:now',
            'observacoes' => 'nullable|string|max:500',
        ]);

        Agendamento::created([
            'id_usuario' => Auth::id(),
            'id_servico' => $request->id_servico,
            'data_agendada' => $request->data_agendada,
            'observacoes' => $request->observacoes,
            'status' => 'pendente',
        ]);

        return redirect()->route('agendamentos.index')->with('success', 'Agendamento criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Agendamento $agendamento)
    {
        //
    }

    /**
     * Editar (cliente so edita quando estiver pendente).
     */
    public function edit($id)
    {
        $agendamento = Agendamento::findOrFail($id);

        if ($agendamento->id_usuario !== Auth::id()){
            abort(403);
        }

        if ($agendamento->status !== 'pendente') {
            abort(403, 'Apenas agendamentos pendentes podem ser editados.');
        }

        return view('agendamentos.edit', compact('agendamento'));
    }

    /**
     * update (cliente).
     */
    public function update(Request $request, $id)
    {
        $agendamento = Agendamento::findOrFail($id);

        if ($agendamento->id_usuario !== Auth::id()){
            abort(403);
        }

        if ($agendamento->status !== 'pendente') {
            abort(403);
        }

        $request->validate([
            'data_agendada' => 'required|date|after:now',
            'observacoes' => 'nullable|string',
        ]);

        $agendamento->update([
            'data_agendada' => $request->data_agendada,
            'observacoes' => $request->observacoes,
        ]);

        return redirect()->route('agendamentos.index')->with('success', 'Agendamento atualizado com sucesso.');
    }
    // estudar mais isso

    public function confirmar($id)
    {
        $agendamento = Agendamento::findOrFail($id);

        // Tem que garantir se o prestador é o dono do agendamento
        if ($agendamento->servico->id_prestador !== Auth::user()->prestador->id_prestador) {
            abort(403, 'Acesso negado.');
        }

        $agendamento->status = 'confirmada';
        $agendamento->save();

        return redirect()->back()->with('success', 'Agendamento confirmado com sucesso.');
    }

    public function cancelar($id)
    {
        $agendamento = Agendamento::dindOrail($id);
        $user = Auth::user();

        $isCliente = $agendamento->id_usuario == $user->id_usuario;
        $isPrestadorDono = $user->tipo === 'prestador' && $agendamento->servico->id_prestador == $user->prestador->id_prestador;
    }

    /**
     * Deletar agendamento.
     */
    public function destroy($id)
    {
        $agendamento = Agendamento::find0rFail($id);

        if ($agendamento->id_usuario !== Auth::id()) {
            abort(403);
        }

        $agendamento->delete();

        return redirect()->route('agendamentos.index')->with('success', 'Agendamento cancelado com sucesso.');

        if (!$isCliente && !$isPrestadorDono) {
            abort(403, 'Acesso negado.');
        }

        $agendamento->status = 'cancelada';
        $agendamento->save();

        return redirect()->back()->with('success', 'Agendamento cancelado com sucesso.');
    }
}
