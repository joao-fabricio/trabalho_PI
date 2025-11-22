<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class AuthController extends Controller
{
    //Formulário de login
    public function formLogin()
    {
        return view('auth.login');
    }

    //Processa o login
    public function login(Request $request)
    {
        // validação básica
        $credenciais = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],

        ]);

        //tentar autenticar
        if (Auth::attempt($credenciais)) {
            return redirect('/dashboard');
    }

    return back()->withErrors([
        'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
    ]);
    }
}
