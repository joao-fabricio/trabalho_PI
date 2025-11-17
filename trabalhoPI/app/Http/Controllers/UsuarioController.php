<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Mostra a lista de usuários apenas para admin.
     */
    public function index()
    {
        if (Auth::user()->tipo !== 'admin') {
            return redirect()->route('home')->with('error', 'Acesso negado.');
        }

        $usuarios = Usuario::all();
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Mostra formulário de criação de novo usuário.
     */
    public function create()
    {
        return view('usuarios.create');
    }

    /**
     * Salva o novo usuário no banco.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:usuarios,email',
            'senha' => 'required|string|min:8|confirmed',
            'tipo' => 'required|in:candidato,empresa,prestador,admin',
        ]);

        Usuario::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'senha' => Hash::make($request->senha),
            'tipo' => $request->tipo,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuário criado com sucesso.');
    }

    /**
     * Perfil do usuário logado.
     */

    public function perfil()
    {
        $usuario = Auth::user();
        return view('usuarios.perfil', compact('usuario'));
    }

    /**
     * Formulário de edição do próprio usuário logado.
     */
    public function edit()
    {
        $usuario = Auth::user();
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Atualiza os dados do usuário logado.
     */
    public function update(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([

            'nome' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:usuarios,email,' . $usuario->id_usuario . ',id_usuario',
            'senha' => 'nullable|string|min:8|confirmed',
            
        ]);

        $usuario = Usuario::findOrFail(Auth::id());
        $usuario->nome = $request->nome;
        $usuario->email = $request->email;

        if ($request->senha) {
            $usuario->senha = Hash::make($request->senha);
        }

        $usuario->save();

        //erro no save, se liga
        return redirect()->route('usuarios.perfil')->with('success', 'Perfil atualizado com sucesso.');
    }

    /**
     * Desativar conta.
     */
    public function destroy($id_usuario)
    {
        $usuario = Usuario::findOrFail($id_usuario);

        if(Auth::id() != $id_usuario && Auth::user()->tipo !== 'admin') {
            return response()->json(['message' => 'Acesso negado.'], 400);
        }

        $usuario->ativo = false;
        $usuario->save();

        return response()->json(['message' => 'Conta desativada com sucesso.']);
    }
}
