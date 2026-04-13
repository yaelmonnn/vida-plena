<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        $adminId = session('admin_id');

        if ($adminId) {
            $admin = Administrador::where('Id', $adminId)
                ->where('activo', 1)
                ->first();

            if ($admin) {
                return redirect()->route('admin.dashboard');
            }

            // Sesión corrupta, limpiar
            session()->forget([
                'admin_id', 'admin_nombre', 'admin_email', 'admin_rol', 'admin_perms',
            ]);
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'El correo es obligatorio.',
            'email.email'       => 'Ingresa un correo válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        $admin = Administrador::with('rol')
            ->where('email', $request->email)
            ->where('activo', 1)
            ->first();


        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Credenciales incorrectas.']);
        }

        // Guardar en sesión
        session([
            'admin_id'     => $admin->Id,
            'admin_nombre' => $admin->nombre . ' ' . $admin->apellido,
            'admin_email'  => $admin->email,
            'admin_rol'    => $admin->rol->nombre ?? 'admin',
            'admin_perms'  => $admin->rol ? $admin->rol->toArray() : [],
        ]);


        return redirect()->route('admin.dashboard')
            ->with('success', '¡Bienvenido, ' . $admin->nombre . '!');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget([
            'admin_id', 'admin_nombre', 'admin_email', 'admin_rol', 'admin_perms',
        ]);

        return redirect()->route('admin.login')
            ->with('success', 'Sesión cerrada correctamente.');
    }
}
