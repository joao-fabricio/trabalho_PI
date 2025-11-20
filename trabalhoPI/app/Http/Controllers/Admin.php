<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Usuario;
use Illuminate\Http\Request;



class AdminController extends Controller
{
    public function index()
    {
        return Admin::with('usuario')->get();
    }

    //Cria um admin
    public function store(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'cargo' => 'nullable|string|max:255',
        ]);

        $admin = Admin::create([
            'id_usuario' => $request->id_usuario,
            'cargo' => $request->cargo ?? 'Administrador',
            //estudar essa parte
            'ultimo_login' => null,
            'ip_ultimo_login' => null,
            'login_count' => 0,
        ]);

        return response()->json($admin, 201);
    }

    // Exibe um admin específico
    public function show($id)
    {
        return Admin::with('usuario')->findOrFail($id);
    }

    // Atualiza dados do admin
    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'cargo' => 'nullable|string|max:255',
        ]);

        $admin->update($request->only('cargo'));

        return response()->json($admin, 200);
    }

    // Excluir admin
    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return response()->json(['message' => 'Admin removido com sucesso.'], 200);
    }

    /**
     * Atualiza as informações de login do admin
     * (último login, IP e contagem de logins).
     */
    public function registrarLogin(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $admin->update([
            'ultimo_login' => now(),
            'ip_ultimo_login' => $request->ip(),
            'login_count' => $admin->login_count + 1,
        ]);

        return response()->json([
            'message' => 'Login registrado com sucesso.',
            'admin' => $admin,
        ]);
        //estudar essa parte de registrar login
    }

}
