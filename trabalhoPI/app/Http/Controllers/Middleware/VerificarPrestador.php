<?php 

namespace App\Http\middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VerificarPrestador
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->prestador){
            return redirect()->route('home')->with('error', 'Acesso restrito a prestadores.');
        }

        return $next($request);
    }
}

?>