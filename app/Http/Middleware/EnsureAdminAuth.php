<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Administrador;

class EnsureAdminAuth
{
    public function handle(Request $request, Closure $next)
    {

        $adminId = session('admin_id');

        if (!$adminId) {
            return redirect()->route('admin.login')
                ->with('info', 'Debes iniciar sesión como administrador.');
        }

        // Verificar que el admin existe y sigue activo en BD
        $admin = Administrador::where('Id', $adminId)
            ->where('activo', 1)
            ->first();

        if (!$admin) {
            // Limpiar sesión corrupta o inválida
            $request->session()->forget([
                'admin_id', 'admin_nombre', 'admin_email', 'admin_rol', 'admin_perms',
            ]);
            return redirect()->route('admin.login')
                ->with('info', 'Sesión administrativa inválida.');
        }

        return $next($request);
    }
}
