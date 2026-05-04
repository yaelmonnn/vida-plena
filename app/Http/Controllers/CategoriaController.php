<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Models\RolAdmin;

class CategoriaController extends Controller
{
    public function index(): View
    {
        $categorias = collect(DB::select("
            SELECT Id, categoria, icono, estado, fr
            FROM categoria
            ORDER BY Id DESC
        "));

        $modulos = RolAdmin::modulosPorUsuario(session('admin_id'));

        return view('admin.categorias.form_categorias', [
            'categorias' => $categorias,
            'modulos'    => $modulos,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoria' => 'required|string|max:60',
            'icono'     => 'nullable|string|max:100',
        ]);

        DB::insert("
            INSERT INTO categoria (categoria, estado, icono, fr)
            VALUES (?, ?, ?, GETDATE())
        ", [
            $request->categoria,
            $request->has('estado') ? 1 : 0,
            $request->icono ?: null,
        ]);

        return redirect()->route('admin.categorias')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'categoria' => 'required|string|max:60',
            'icono'     => 'nullable|string|max:100',
        ]);

        $quiereDesactivar = !$request->has('estado');

        // Si intenta desactivar, verificar que no haya productos activos con esta categoría
        if ($quiereDesactivar) {
            $productosActivos = DB::selectOne("
                SELECT COUNT(*) AS total
                FROM producto
                WHERE categoria_id = ? AND activo = 1
            ", [$id])->total;

            if ($productosActivos > 0) {
                return redirect()->route('admin.categorias')
                    ->with('error', "No se puede desactivar la categoría porque tiene {$productosActivos} producto(s) activo(s) asignado(s).");
            }
        }

        DB::update("
            UPDATE categoria
            SET categoria = ?,
                icono     = ?,
                estado    = ?
            WHERE Id = ?
        ", [
            $request->categoria,
            $request->icono ?: null,
            $quiereDesactivar ? 0 : 1,
            $id,
        ]);

        return redirect()->route('admin.categorias')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(int $id)
    {
        // Verificar que no haya productos activos con esta categoría
        $productosActivos = DB::selectOne("
            SELECT COUNT(*) AS total
            FROM producto
            WHERE categoria_id = ? AND activo = 1
        ", [$id])->total;

        if ($productosActivos > 0) {
            return redirect()->route('admin.categorias')
                ->with('error', "No se puede eliminar la categoría porque tiene {$productosActivos} producto(s) activo(s) asignado(s).");
        }

        // Soft delete: desactiva la categoría
        DB::update("UPDATE categoria SET estado = 0 WHERE Id = ?", [$id]);

        return redirect()->route('admin.categorias')
            ->with('success', 'Categoría eliminada correctamente.');
    }
}
