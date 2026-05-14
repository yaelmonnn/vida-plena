<?php

namespace App\Http\Controllers;

use App\Models\Deseo;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeseoController extends Controller
{
    /** Vista "Mis Deseos" */
    public function index()
    {
        $usuarioId = Auth::guard('usuario')->id();

        $rows = \Illuminate\Support\Facades\DB::select("
            SELECT
                d.Id          AS deseo_id,
                d.agregado_en,
                p.Id          AS producto_id,
                p.nombre,
                p.precio,
                p.tipo,
                p.calificacion,
                p.cantidad_disponible,
                c.categoria
            FROM deseos d
            INNER JOIN producto  p ON p.Id = d.producto_id
            INNER JOIN categoria c ON c.Id = p.categoria_id
            WHERE d.usuario_id = ?
            ORDER BY d.agregado_en DESC
        ", [$usuarioId]);

        // Cargar imágenes para cada producto
        foreach ($rows as $row) {
            $row->imagenes = \Illuminate\Support\Facades\DB::select(
                "SELECT ruta, alt_text FROM imagenes_producto WHERE producto_id = ? ORDER BY orden ASC",
                [$row->producto_id]
            );
        }

        return view('deseos.index', ['deseos' => collect($rows)]);
    }

    /** Toggle: agrega si no existe, quita si ya existe */
    public function toggle(Request $request)
    {
        $request->validate([
            'producto_id' => ['required', 'integer', 'exists:producto,Id'],
        ]);

        $usuarioId  = Auth::guard('usuario')->id();
        $productoId = $request->producto_id;

        $existente = Deseo::where('usuario_id', $usuarioId)
                          ->where('producto_id', $productoId)
                          ->first();

        if ($existente) {
            $existente->delete();
            $enDeseos = false;
            $mensaje  = 'Eliminado de tus deseos.';
        } else {
            Deseo::create([
                'usuario_id'  => $usuarioId,
                'producto_id' => $productoId,
                'agregado_en' => now(),
            ]);
            $enDeseos = true;
            $mensaje  = '¡Guardado en tus deseos!';
        }

        $totalDeseos = Deseo::where('usuario_id', $usuarioId)->count();

        return response()->json([
            'ok'          => true,
            'en_deseos'   => $enDeseos,
            'mensaje'     => $mensaje,
            'total_deseos'=> $totalDeseos,
        ]);
    }

    /** Quitar desde la vista Mis Deseos (para el botón eliminar de esa página) */
    public function quitar($productoId)
    {
        Deseo::where('usuario_id', Auth::guard('usuario')->id())
             ->where('producto_id', $productoId)
             ->delete();

        $totalDeseos = Deseo::where('usuario_id', Auth::guard('usuario')->id())->count();

        return response()->json(['ok' => true, 'total_deseos' => $totalDeseos]);
    }

    /** IDs de productos en deseos del usuario (para pintar corazones activos) */
    public function misIds()
    {
        if (!Auth::guard('usuario')->check()) {
            return response()->json(['ids' => []]);
        }

        $ids = Deseo::where('usuario_id', Auth::guard('usuario')->id())
                    ->pluck('producto_id');

        return response()->json(['ids' => $ids]);
    }
}
