<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\StoreCategoriaRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Seccion;
use App\Models\RolAdmin;

class ProductoController extends Controller
{
    public function formProductos(): View {

        $categorias = Categoria::activas();

        $estados = collect(DB::select("
            SELECT Id, estado_nombre
            FROM estado_producto
            WHERE estado = 1
        "));

        $modulos = RolAdmin::modulosPorUsuario(session('admin_id'));
        $productos = Producto::traerTodosActivos();

        return view('admin.productos.form', [
            'categorias' => $categorias,
            'estados' => $estados,
            'modulos' => $modulos,
            'productos' => $productos
        ]);
    }

    public function update(Request $request, int $id)
    {
        DB::update("
            UPDATE producto
            SET nombre              = ?,
                categoria_id        = ?,
                estado_id           = ?,
                precio              = ?,
                cantidad_disponible = ?,
                descripcion         = ?,
                activo              = ?
            WHERE Id = ?
        ", [
            $request->nombre,
            $request->categoria_id,
            $request->estado_id,
            $request->precio,
            $request->cantidad_disponible,
            $request->descripcion,
            $request->has('activo') ? 1 : 0,
            $id,
        ]);

        return redirect()->route('admin.productos')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function imagenes(int $id): JsonResponse
    {
        $imagenes = Producto::imagenes($id);
        return response()->json($imagenes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'             => 'required|string|max:255',
            'categoria_id'       => 'required|integer',
            'estado_id'          => 'required|integer',
            'precio'             => 'required|numeric|min:0',
            'cantidad_disponible'=> 'required|integer|min:0',
            'descripcion'        => 'nullable|string',
            'imagenes'           => 'nullable|array|max:5',
            'imagenes.*'         => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Insertar producto y obtener el ID generado
        DB::insert("
            INSERT INTO producto (nombre, categoria_id, tipo, estado_id, precio, cantidad_disponible, descripcion, activo, calificacion, fr)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, GETDATE())
        ", [
            $request->nombre,
            $request->categoria_id,
            'producto',
            $request->estado_id,
            $request->precio,
            $request->cantidad_disponible,
            $request->descripcion,
            $request->has('activo') ? 1 : 0,
            0,
        ]);

        $nuevoId = DB::selectOne("SELECT TOP 1 Id FROM producto ORDER BY Id DESC")->Id;

        // Guardar imágenes si las hay
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $orden => $archivo) {
                $nombreArchivo = time() . '_' . $orden . '_' . Str::slug($request->nombre) . '.' . $archivo->getClientOriginalExtension();
                $archivo->move(public_path('images'), $nombreArchivo);

                DB::insert(
                    "INSERT INTO imagenes_producto (producto_id, ruta, orden, alt_text, fecha_alta)
                    VALUES (?, ?, ?, ?, GETDATE())",
                    [$nuevoId, $nombreArchivo, $orden, $request->nombre]
                );
            }
        }

        return redirect()->route('admin.productos')
            ->with('success', 'Producto creado correctamente.');
    }

    public function agregarImagenes(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'imagenes'   => 'required|array|max:5',
            'imagenes.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Orden actual más alto
        $ultimoOrden = DB::selectOne(
            "SELECT ISNULL(MAX(orden), -1) AS ultimo FROM imagenes_producto WHERE producto_id = ?",
            [$id]
        )->ultimo;

        // Contar cuántas tiene ya
        $totalActual = DB::selectOne(
            "SELECT COUNT(*) AS total FROM imagenes_producto WHERE producto_id = ?",
            [$id]
        )->total;

        $archivosPermitidos = 5 - $totalActual;

        if ($archivosPermitidos <= 0) {
            return response()->json(['error' => 'El producto ya tiene 5 imágenes.'], 422);
        }

        $archivos = array_slice($request->file('imagenes'), 0, $archivosPermitidos);
        $nombre   = DB::selectOne("SELECT nombre FROM producto WHERE Id = ?", [$id])->nombre;

        foreach ($archivos as $i => $archivo) {
            $nombreArchivo = time() . '_' . $i . '_' . Str::slug($nombre) . '.' . $archivo->getClientOriginalExtension();
            $archivo->move(public_path('images'), $nombreArchivo);

            DB::insert(
                "INSERT INTO imagenes_producto (producto_id, ruta, orden, alt_text, fecha_alta)
                VALUES (?, ?, ?, ?, GETDATE())",
                [$id, $nombreArchivo, ++$ultimoOrden, $nombre]
            );
        }

        return response()->json(Producto::imagenes($id));
    }

    public function eliminarImagen(int $imagenId): JsonResponse
    {
        $imagen = DB::selectOne(
            "SELECT * FROM imagenes_producto WHERE Id = ?",
            [$imagenId]
        );

        if (!$imagen) {
            return response()->json(['error' => 'Imagen no encontrada.'], 404);
        }

        // Borrar archivo físico
        $path = public_path("images/{$imagen->ruta}");
        if (file_exists($path)) {
            unlink($path);
        }

        // Borrar registro
        DB::delete("DELETE FROM imagenes_producto WHERE Id = ?", [$imagenId]);

        // Reordenar: las imágenes con orden > eliminada bajan 1
        DB::update(
            "UPDATE imagenes_producto SET orden = orden - 1
            WHERE producto_id = ? AND orden > ?",
            [$imagen->producto_id, $imagen->orden]
        );

        return response()->json(Producto::imagenes($imagen->producto_id));
    }

    public function destroy(int $id)
    {
        // Soft delete: solo desactiva
        DB::update("UPDATE producto SET activo = 0 WHERE Id = ?", [$id]);

        return redirect()->route('admin.productos')
            ->with('success', 'Producto eliminado correctamente.');
    }



    //Servicios ----------------------------------

    public function formServicios(): View
    {
        $categorias = Categoria::activas();

        $estados = collect(DB::select("
            SELECT Id, estado_nombre
            FROM estado_producto
            WHERE estado = 1
        "));

        $modulos = RolAdmin::modulosPorUsuario(session('admin_id'));
        $productos = Producto::traerTodosServiciosActivos();

        return view('admin.servicios.form_servicios', [
            'categorias' => $categorias,
            'estados'    => $estados,
            'modulos'    => $modulos,
            'productos'  => $productos,
        ]);
    }

    public function storeServicio(Request $request)
    {
        $request->validate([
            'nombre'              => 'required|string|max:255',
            'categoria_id'        => 'required|integer',
            'precio'              => 'required|numeric|min:0',
            'cantidad_disponible' => 'required|integer|min:0',
            'descripcion'         => 'nullable|string',
            'imagenes'            => 'nullable|array|max:5',
            'imagenes.*'          => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        DB::insert("
            INSERT INTO producto (nombre, categoria_id, tipo, estado_id, precio, cantidad_disponible, descripcion, activo, calificacion, fr)
            VALUES (?, ?, 'servicio', ?, ?, ?, ?, ?, ?, GETDATE())
        ", [
            $request->nombre,
            $request->categoria_id,
            1, // estado_id por defecto (activo)
            $request->precio,
            $request->cantidad_disponible,
            $request->descripcion,
            $request->has('activo') ? 1 : 0,
            0,
        ]);

        $nuevoId = DB::selectOne("SELECT TOP 1 Id FROM producto ORDER BY Id DESC")->Id;

        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $orden => $archivo) {
                $nombreArchivo = time() . '_' . $orden . '_' . Str::slug($request->nombre) . '.' . $archivo->getClientOriginalExtension();
                $archivo->move(public_path('images'), $nombreArchivo);

                DB::insert(
                    "INSERT INTO imagenes_producto (producto_id, ruta, orden, alt_text, fecha_alta)
                    VALUES (?, ?, ?, ?, GETDATE())",
                    [$nuevoId, $nombreArchivo, $orden, $request->nombre]
                );
            }
        }

        return redirect()->route('admin.servicios')
            ->with('success', 'Servicio creado correctamente.');
    }

    public function updateServicio(Request $request, int $id)
    {
        DB::update("
            UPDATE producto
            SET nombre              = ?,
                categoria_id        = ?,
                precio              = ?,
                cantidad_disponible = ?,
                descripcion         = ?,
                activo              = ?
            WHERE Id = ? AND tipo = 'servicio'
        ", [
            $request->nombre,
            $request->categoria_id,
            $request->precio,
            $request->cantidad_disponible,
            $request->descripcion,
            $request->has('activo') ? 1 : 0,
            $id,
        ]);

        return redirect()->route('admin.servicios')
            ->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroyServicio(int $id)
    {
        DB::update("UPDATE producto SET activo = 0 WHERE Id = ? AND tipo = 'servicio'", [$id]);

        return redirect()->route('admin.servicios')
            ->with('success', 'Servicio eliminado correctamente.');
    }




}
