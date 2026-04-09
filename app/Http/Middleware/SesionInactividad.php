<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SesionInactividad
{
    protected int $minutosInactividad = 10;

    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('usuario')->check()) {
            $ultimaActividad = session('ultima_actividad');

            if ($ultimaActividad && now()->diffInMinutes($ultimaActividad) >= $this->minutosInactividad) {
                Auth::guard('usuario')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login.usuario')
                    ->withErrors('Tu sesión expiró por inactividad.');
            }

            session(['ultima_actividad' => now()]);
        }

        return $next($request);
    }
}
