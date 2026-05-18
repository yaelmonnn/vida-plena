<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\RolAdmin;
use Illuminate\Http\Request;

class AdminClienteController extends Controller
{
    public function index()
    {
        $clientes = Usuario::all();

        $modulos = RolAdmin::modulosPorUsuario(session('admin_id'));

        return view('admin.clientes.index', compact(
            'clientes',
            'modulos'
        ));
    }

    public function update(Request $request, $id)
    {
        $cliente = Usuario::findOrFail($id);

        $cliente->update([
            'activo' => $request->has('activo')
        ]);

        return back()->with('success', 'Cliente actualizado.');
    }

    public function destroy($id)
    {
        $cliente = Usuario::findOrFail($id);

        $cliente->activo = 0;
        $cliente->save();

        return back()->with('success', 'Cliente dado de baja.');
    }
}
