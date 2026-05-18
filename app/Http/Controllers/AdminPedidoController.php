<?php
// app/Http/Controllers/AdminPedidoController.php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\PedidoEnviadoMail;        // correo que crearás (ver abajo)
use Carbon\Carbon;
use App\Models\RolAdmin;
use Illuminate\Support\Facades\DB;

class AdminPedidoController extends Controller
{
    /**
     * Listado paginado de todos los pedidos.
     */
    public function index()
    {
        $pedidos = Pedido::with(['detalles'])
            ->orderBy('fr', 'desc')
            ->paginate(20);

        // Conteo por estado para las tarjetas resumen
        $conteos = Pedido::selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        // $modulos lo pasa el middleware/sidebar igual que en otros controladores admin
        $modulos = RolAdmin::modulosPorUsuario(session('admin_id'));

        return view('admin.pedidos', compact('pedidos', 'conteos', 'modulos'));
    }

    /**
     * Devuelve el detalle de un pedido en JSON para el modal.
     */
    public function show(int $id)
    {
        $pedido = Pedido::with('detalles')->findOrFail($id);

        return response()->json([
            'Id'               => $pedido->Id,
            'nombre_envio'     => $pedido->nombre_envio,
            'email_envio'      => $pedido->email_envio,
            'telefono_envio'   => $pedido->telefono_envio,
            'calle_envio'      => $pedido->calle_envio,
            'colonia_envio'    => $pedido->colonia_envio,
            'ciudad_envio'     => $pedido->ciudad_envio,
            'cp_envio'         => $pedido->cp_envio,
            'estado'           => $pedido->estado,
            'total'            => $pedido->total,
            'stripe_payment_id'=> $pedido->stripe_payment_id,
            'fecha_fmt'        => Carbon::parse($pedido->fr)
                                    ->timezone('America/Mexico_City')
                                    ->locale('es')
                                    ->isoFormat('D [de] MMMM YYYY, HH:mm'),
            'detalles'         => $pedido->detalles->map(fn($d) => [
                'nombre_producto'  => $d->nombre_producto,
                'tipo_producto'    => $d->tipo_producto,
                'precio_unitario'  => $d->precio_unitario,
                'cantidad'         => $d->cantidad,
                'subtotal'         => $d->subtotal,
                'fecha_servicio'   => $d->fecha_servicio,
            ]),
        ]);
    }

    /**
     * Marca el pedido como "enviado" y manda correo al cliente.
     */
    public function enviar(int $id)
    {
        $pedido = Pedido::with('detalles')->findOrFail($id);

        if ($pedido->estado !== 'pagado') {
            return response()->json([
                'ok'      => false,
                'message' => 'Este pedido no puede marcarse como enviado (estado actual: ' . $pedido->estado . ').',
            ], 422);
        }

        $pedido->estado = 'enviado';
        $pedido->save();

        try {
            Mail::to($pedido->email_envio)->send(new PedidoEnviadoMail($pedido));
        } catch (\Exception $e) {
            Log::error("No se pudo enviar el correo del pedido #{$pedido->Id}: " . $e->getMessage());
        }

        return response()->json(['ok' => true]);
    }
}
