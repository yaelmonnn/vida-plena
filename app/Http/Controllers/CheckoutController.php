<?php
// app/Http/Controllers/CheckoutController.php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Illuminate\Support\Facades\Mail;
use App\Mail\PedidoConfirmadoMail;
use Stripe\PaymentIntent;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    // ──────────────────────────────────────────────────────────
    // Helper: resuelve los items según si viene de compra directa
    // o del carrito normal. Devuelve una colección uniforme con:
    //   ->producto, ->cantidad
    // ──────────────────────────────────────────────────────────
    private function resolverItems($usuarioId): \Illuminate\Support\Collection
    {
        $directa = session('compra_directa');

        if ($directa) {
            $producto = Producto::with('imagenes')->find($directa['producto_id']);

            if (!$producto) return collect();

            // Fabricamos un objeto anónimo con la misma forma que un item de carrito
            return collect([(object)[
                'producto_id' => $producto->Id,
                'producto'    => $producto,
                'cantidad'    => $directa['cantidad'],
            ]]);
        }

        return Carrito::with(['producto.imagenes'])
                      ->where('usuario_id', $usuarioId)
                      ->get();
    }

    // ──────────────────────────────────────────────────────────
    // NUEVO — Comprar ahora (desde modal de producto)
    // Guarda el producto en sesión y redirige al checkout
    // ──────────────────────────────────────────────────────────
    public function comprarAhora(Request $request, $id)
    {
        $usuario  = Auth::guard('usuario')->user();
        $producto = Producto::findOrFail($id);

        if (!$producto->activo || $producto->cantidad_disponible < 1) {
            return redirect()->route('tienda')
                             ->with('error', 'Este producto no está disponible.');
        }

        // Limpiamos cualquier compra directa anterior y guardamos la nueva
        session(['compra_directa' => [
            'producto_id' => $producto->Id,
            'cantidad'    => 1,
        ]]);

        return redirect()->route('checkout.index');
    }

    // ──────────────────────────────────────────────────────────
    // PASO 0 — Mostrar resumen del checkout
    // ──────────────────────────────────────────────────────────
    public function index()
    {
        $usuario = Auth::guard('usuario')->user();
        $items   = $this->resolverItems($usuario->Id);

        if ($items->isEmpty()) {
            return redirect()->route('carrito')
                             ->with('info', 'Tu carrito está vacío.');
        }

        $subtotal = $items->sum(fn($i) => $i->producto->precio * $i->cantidad);
        $total    = $subtotal;

        // Flag para que la vista sepa si es compra directa
        $esCompraDirecta = (bool) session('compra_directa');

        return view('checkout.index', compact('items', 'subtotal', 'total', 'esCompraDirecta'));
    }

    // ──────────────────────────────────────────────────────────
    // PASO 1 — Crear PaymentIntent en Stripe (sin tocar BD)
    // ──────────────────────────────────────────────────────────
    public function crearIntent(Request $request)
    {
        $request->validate([
            'envio.nombre'   => ['required', 'string', 'max:100'],
            'envio.apellido' => ['required', 'string', 'max:100'],
            'envio.email'    => ['required', 'email'],
            'envio.calle'    => ['required', 'string', 'max:200'],
        ]);

        $usuario = Auth::guard('usuario')->user();
        $items   = $this->resolverItems($usuario->Id);

        if ($items->isEmpty()) {
            return response()->json(['ok' => false, 'message' => 'No hay productos en el pedido.'], 422);
        }

        foreach ($items as $item) {
            if ($item->producto->cantidad_disponible < $item->cantidad) {
                return response()->json([
                    'ok'      => false,
                    'message' => "Sin stock suficiente para: {$item->producto->nombre}",
                ], 422);
            }
        }

        $total = $items->sum(fn($i) => $i->producto->precio * $i->cantidad);

        try {
            $intent = PaymentIntent::create([
                'amount'      => (int) round($total * 100),
                'currency'    => 'mxn',
                'description' => "Compra Vida Plena - {$usuario->email}",
                'metadata'    => [
                    'usuario_id' => $usuario->Id,
                    'email'      => $usuario->email,
                ],
            ]);

            session([
                'checkout_envio'  => $request->input('envio'),
                'checkout_fechas' => $request->input('fechas_servicio', []),
                'checkout_intent' => $intent->id,
            ]);

            return response()->json([
                'ok'            => true,
                'client_secret' => $intent->client_secret,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'ok'      => false,
                'message' => 'Error al iniciar el pago: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ──────────────────────────────────────────────────────────
    // PASO 2 — Stripe confirmó en el frontend → crear pedido en BD
    // ──────────────────────────────────────────────────────────
    public function confirmar(Request $request)
    {
        $request->validate([
            'payment_intent_id' => ['required', 'string'],
        ]);

        $usuario = Auth::guard('usuario')->user();

        // 1. Verificar con Stripe que el pago fue exitoso
        try {
            $intent = PaymentIntent::retrieve($request->payment_intent_id);
        } catch (\Exception $e) {
            return response()->json([
                'ok'      => false,
                'message' => 'No se pudo verificar el pago con Stripe.',
            ], 500);
        }

        if ($intent->status !== 'succeeded') {
            return response()->json([
                'ok'      => false,
                'message' => 'El pago no fue aprobado. Estado: ' . $intent->status,
            ], 422);
        }

        // 2. Evitar duplicados
        $existente = Pedido::where('stripe_payment_id', $intent->id)->first();
        if ($existente) {
            return response()->json(['ok' => true, 'pedido_id' => $existente->Id]);
        }

        // 3. Datos de sesión
        $envio  = session('checkout_envio', []);
        $fechas = session('checkout_fechas', []);

        if (empty($envio)) {
            return response()->json([
                'ok'      => false,
                'message' => 'Sesión expirada. Contacta soporte con el ID: ' . $intent->id,
            ], 422);
        }

        // 4. Resolver items (carrito o compra directa)
        $items           = $this->resolverItems($usuario->Id);
        $esCompraDirecta = (bool) session('compra_directa');

        if ($items->isEmpty()) {
            return response()->json([
                'ok'      => false,
                'message' => 'El pedido ya fue procesado.',
            ], 422);
        }

        $total = $items->sum(fn($i) => $i->producto->precio * $i->cantidad);

        // 5. Crear pedido en BD
        DB::beginTransaction();
        try {
            $pedido = Pedido::create([
                'usuario_id'        => $usuario->Id,
                'estado'            => 'pagado',
                'total'             => $total,
                'nombre_envio'      => trim(($envio['nombre'] ?? '') . ' ' . ($envio['apellido'] ?? '')),
                'email_envio'       => $envio['email']    ?? $usuario->email,
                'telefono_envio'    => $envio['telefono'] ?? null,
                'calle_envio'       => $envio['calle']    ?? '',
                'colonia_envio'     => $envio['colonia']  ?? null,
                'ciudad_envio'      => $envio['ciudad']   ?? null,
                'cp_envio'          => $envio['cp']       ?? null,
                'stripe_payment_id' => $intent->id,
                'fr'                => now(),
                'pagado_en'         => now(),
            ]);

            foreach ($items as $item) {
                DetallePedido::create([
                    'pedido_id'       => $pedido->Id,
                    'producto_id'     => $item->producto_id,
                    'nombre_producto' => $item->producto->nombre,
                    'tipo_producto'   => $item->producto->tipo,
                    'precio_unitario' => $item->producto->precio,
                    'cantidad'        => $item->cantidad,
                    'subtotal'        => $item->producto->precio * $item->cantidad,
                    'fecha_servicio'  => $fechas[$item->producto_id] ?? null,
                ]);

                Producto::where('Id', $item->producto_id)
                        ->decrement('cantidad_disponible', $item->cantidad);
            }

            // Limpiar carrito solo si NO era compra directa
            if (!$esCompraDirecta) {
                Carrito::where('usuario_id', $usuario->Id)->delete();
            }

            session()->forget([
                'checkout_envio',
                'checkout_fechas',
                'checkout_intent',
                'compra_directa',   // ← limpia también la compra directa si existía
            ]);

            DB::commit();

            try {
                $pedido->load('detalles');
                $emailDestino = $envio['email'] ?? $usuario->email;
                Mail::to($emailDestino)->send(new PedidoConfirmadoMail($pedido));
            } catch (\Exception $e) {
                Log::error('No se pudo enviar el correo del pedido #' . $pedido->Id . ': ' . $e->getMessage());
            }

            return response()->json(['ok' => true, 'pedido_id' => $pedido->Id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'ok'      => false,
                'message' => 'Pago recibido pero error interno. Contacta soporte con ID: ' . $intent->id,
            ], 500);
        }
    }

    // ──────────────────────────────────────────────────────────
    // Página de éxito
    // ──────────────────────────────────────────────────────────
    public function exito(Request $request)
    {
        $pedidoId = $request->query('pedido');
        $pedido   = null;

        if ($pedidoId) {
            $pedido = Pedido::with('detalles')
                            ->where('Id', $pedidoId)
                            ->where('usuario_id', Auth::guard('usuario')->id())
                            ->where('estado', 'pagado')
                            ->first();
        }

        return view('checkout.exito', compact('pedido'));
    }

    // ──────────────────────────────────────────────────────────
    // Webhook de Stripe
    // ──────────────────────────────────────────────────────────
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig     = $request->header('Stripe-Signature');
        $secret  = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sig, $secret);
        } catch (\Exception $e) {
            return response('Webhook error', 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $intent = $event->data->object;

            if (Pedido::where('stripe_payment_id', $intent->id)->exists()) {
                return response('Already processed', 200);
            }
        }

        return response('OK', 200);
    }
}
