<?php
// app/Http/Controllers/PedidoController.php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with(['detalles.producto.imagenes'])
            ->where('usuario_id', Auth::guard('usuario')->id())
            ->orderBy('fr', 'desc')
            ->paginate(10);

        return view('perfil.pedidos', compact('pedidos'));
    }
}
