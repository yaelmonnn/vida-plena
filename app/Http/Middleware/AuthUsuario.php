<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthUsuario
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('usuario')->check()) {
            return redirect()->route('login.usuario');
        }

        $usuario = Auth::guard('usuario')->user();

        if (!$usuario->activo) {
            Auth::guard('usuario')->logout();
            return redirect()->route('login.usuario')
                ->withErrors(['email' => 'Cuenta desactivada.']);
        }

        return $next($request);
    }
}
