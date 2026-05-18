<?php
// app/Http/Controllers/CarritoController.php
namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarritoController extends Controller
{
    public function index()
    {
        $items = Carrito::with('producto')
            ->where('usuario_id', Auth::guard('usuario')->id())
            ->orderBy('agregado_en', 'desc')
            ->get();

        $total = $items->sum(fn($i) => $i->producto->precio * $i->cantidad);

        return view('carrito.index', compact('items', 'total'));
    }

    public function agregar(Request $request)
    {
        $request->validate([
            'producto_id' => ['required', 'integer', 'exists:producto,Id'],
            'cantidad'    => ['integer', 'min:1'],
        ]);

        $usuarioId = Auth::guard('usuario')->id();
        $cantidad  = $request->input('cantidad', 1);

        // ── Validar disponibilidad ──────────────────────────────
        $producto = Producto::find($request->producto_id);

        if (!$producto || !$producto->activo) {
            return response()->json([
                'ok'      => false,
                'message' => 'Este producto no está disponible.',
            ], 422);
        }

        if ($producto->cantidad_disponible < 1) {
            return response()->json([
                'ok'      => false,
                'message' => 'Lo sentimos, este producto está agotado.',
            ], 422);
        }

        // Si ya está en el carrito, validar que no exceda el stock
        $item = Carrito::where('usuario_id', $usuarioId)
                    ->where('producto_id', $request->producto_id)
                    ->first();

        $cantidadEnCarrito = $item ? $item->cantidad : 0;

        if (($cantidadEnCarrito + $cantidad) > $producto->cantidad_disponible) {
            return response()->json([
                'ok'      => false,
                'message' => "Solo hay {$producto->cantidad_disponible} unidad(es) disponible(s) y ya tienes {$cantidadEnCarrito} en tu carrito.",
            ], 422);
        }
        // ───────────────────────────────────────────────────────

        if ($item) {
            $item->increment('cantidad', $cantidad);
        } else {
            Carrito::create([
                'usuario_id'  => $usuarioId,
                'producto_id' => $request->producto_id,
                'cantidad'    => $cantidad,
                'agregado_en' => now(),
            ]);
        }

        $totalItems = Carrito::where('usuario_id', $usuarioId)->sum('cantidad');

        return response()->json(['ok' => true, 'total_items' => $totalItems]);
    }

    public function eliminar($id)
    {
        Carrito::where('Id', $id)
               ->where('usuario_id', Auth::guard('usuario')->id())
               ->delete();

        return back()->with('success', 'Producto eliminado del carrito.');
    }

    public function actualizarCantidad(Request $request, $id)
    {
        $request->validate(['cantidad' => ['required', 'integer', 'min:1']]);

        $item = Carrito::where('Id', $id)
                       ->where('usuario_id', Auth::guard('usuario')->id())
                       ->firstOrFail();

        $item->update(['cantidad' => $request->cantidad]);

        $total = Carrito::with('producto')
            ->where('usuario_id', Auth::guard('usuario')->id())
            ->get()
            ->sum(fn($i) => $i->producto->precio * $i->cantidad);

        return response()->json(['ok' => true, 'total' => $total]);
    }
}
