<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class Producto extends Model
{
    protected $table      = 'producto';
    protected $primaryKey = 'Id';
    public    $timestamps = false;

    // ──────────────────────────────────────────────
    //  CONSULTAS — Productos destacados (catálogo)
    // ──────────────────────────────────────────────

    /**
     * Rango absoluto de precios de todos los productos activos.
     */
    public static function rangoPrecio(): object
    {
        return DB::selectOne("
            SELECT
                ISNULL(MIN(precio), 0) AS minimo,
                ISNULL(MAX(precio), 0) AS maximo
            FROM producto
            WHERE activo = 1
        ");
    }

    /**
     * Listado de productos destacados (calificación 4-5)
     * con filtros opcionales de búsqueda, precio y tipo.
     */
    public static function destacados(
        string $buscar,
        int    $precioMin,
        int    $precioMax,
        string $tipo,
        string $orderSQL
    ): Collection {
        $rows = DB::select("
            SELECT
                p.Id,
                p.tipo,
                c.categoria,
                ep.estado_nombre,
                p.nombre,
                p.calificacion,
                p.descripcion,
                p.precio,
                p.cantidad_disponible
            FROM producto p
            INNER JOIN categoria       c  ON c.Id  = p.categoria_id
            INNER JOIN estado_producto ep ON ep.Id = p.estado_id
            WHERE p.activo = 1
              AND p.calificacion BETWEEN 4 AND 5
              AND (
                  ? = ''
                  OR p.nombre      COLLATE Latin1_General_CI_AI LIKE '%' + ? + '%'
                  OR p.descripcion COLLATE Latin1_General_CI_AI LIKE '%' + ? + '%'
                  OR c.categoria   COLLATE Latin1_General_CI_AI LIKE '%' + ? + '%'
              )
              AND p.precio BETWEEN ? AND ?
              AND (? = '' OR p.tipo = ?)
            ORDER BY {$orderSQL}
        ", [
            $buscar, $buscar, $buscar, $buscar,
            $precioMin, $precioMax,
            $tipo, $tipo,
        ]);

        return collect($rows);
    }

    public static function tienda(
        string $buscar,
        int    $precioMin,
        int    $precioMax,
        string $tipo,
        string $orderSQL
    ) {
        $rows = DB::select("
            SELECT
                p.Id,
                p.tipo,
                c.categoria,
                ep.estado_nombre,
                p.nombre,
                p.calificacion,
                p.descripcion,
                p.precio,
                p.cantidad_disponible
            FROM producto p
            INNER JOIN categoria       c  ON c.Id  = p.categoria_id
            INNER JOIN estado_producto ep ON ep.Id = p.estado_id
            WHERE p.activo = 1
              AND (
                  ? = ''
                  OR p.nombre      COLLATE Latin1_General_CI_AI LIKE '%' + ? + '%'
                  OR p.descripcion COLLATE Latin1_General_CI_AI LIKE '%' + ? + '%'
                  OR c.categoria   COLLATE Latin1_General_CI_AI LIKE '%' + ? + '%'
              )
              AND p.precio BETWEEN ? AND ?
              AND (? = '' OR p.tipo = ?)
            ORDER BY {$orderSQL}
        ", [
            $buscar, $buscar, $buscar, $buscar,
            $precioMin, $precioMax,
            $tipo, $tipo,
        ]);

        return collect($rows);
    }

    /**
     * Sugerencias de búsqueda (máx. 6 resultados).
     */
    public static function sugerencias(string $buscar): array
    {
        return DB::select("
            SELECT TOP 6 p.Id, p.nombre, c.categoria
            FROM producto p
            INNER JOIN categoria c ON c.Id = p.categoria_id
            WHERE p.activo = 1
              AND (
                  p.nombre      COLLATE Latin1_General_CI_AI LIKE '%' + ? + '%'
                  OR p.descripcion COLLATE Latin1_General_CI_AI LIKE '%' + ? + '%'
              )
            ORDER BY p.calificacion DESC
        ", [$buscar, $buscar]);
    }

    // ──────────────────────────────────────────────
    //  CONSULTAS — Detalle / Modal
    // ──────────────────────────────────────────────

    /**
     * Datos completos de un producto para el modal de detalle.
     */
    public static function detalle(int $id): ?object
    {
        return DB::selectOne("
            SELECT p.*, c.categoria, ep.estado_nombre
            FROM producto p
            INNER JOIN categoria       c  ON c.Id  = p.categoria_id
            INNER JOIN estado_producto ep ON ep.Id = p.estado_id
            WHERE p.Id = ?
        ", [$id]);
    }

    /**
     * Imágenes del carrusel de un producto.
     */
    public static function imagenes(int $id): Collection
    {
        $imagenes = DB::select("
            SELECT ruta, alt_text, orden
            FROM imagenes_producto
            WHERE producto_id = ?
            ORDER BY orden ASC
        ", [$id]);

        return collect($imagenes);
    }

    /**
     * Especificaciones técnicas de un producto.
     */
    public static function especificaciones(int $id): Collection
    {
        return collect(DB::select("
            SELECT clave, valor
            FROM especificaciones_producto
            WHERE producto_id = ?
            ORDER BY orden ASC
        ", [$id]));
    }

    /**
     * Nombre de un producto por ID (usado en sugerencias al seleccionar).
     */
    public static function nombrePorId(int $id): ?string
    {
        $row = DB::selectOne("SELECT nombre FROM producto WHERE Id = ?", [$id]);
        return $row?->nombre;
    }

    public static function opiniones(int $id): Collection
    {
        return collect(DB::select("
            SELECT Id, autor, calificacion, comentario, fr
            FROM opinion
            WHERE producto_id = ? AND activo = 1
            ORDER BY fr DESC
        ", [$id]));
    }


}
