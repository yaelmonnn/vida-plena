<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use App\Models\RolAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Administrador::with('rol')->get();

        $roles = RolAdmin::where('activo', 1)->get();

        $modulos = RolAdmin::modulosPorUsuario(session('admin_id'));

        return view('admin.usuarios.index', compact(
            'usuarios',
            'roles',
            'modulos'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'email' => 'required|email|unique:administrador,email',
            'password' => 'required|min:6',
            'rol_id' => 'required'
        ]);

        Administrador::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol_id' => $request->rol_id,
            'activo' => $request->has('activo')
        ]);

        return back()->with('success', 'Administrador creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $admin = Administrador::findOrFail($id);

        if ($admin->Id == session('admin_id')) {
            return back()->withErrors([
                'error' => 'No puedes editar tu propia cuenta.'
            ]);
        }

        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'email' => 'required|email'
        ]);

        $admin->update([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'rol_id' => $request->rol_id,
            'activo' => $request->has('activo')
        ]);

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
            $admin->save();
        }

        return back()->with('success', 'Administrador actualizado.');
    }

    public function destroy($id)
    {
        $admin = Administrador::findOrFail($id);

        if ($admin->Id == session('admin_id')) {
            return back()->withErrors([
                'error' => 'No puedes darte de baja.'
            ]);
        }

        $admin->activo = 0;
        $admin->save();

        return back()->with('success', 'Administrador dado de baja.');
    }
}
