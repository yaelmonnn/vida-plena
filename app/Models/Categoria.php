<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class Categoria extends Model
{
    protected $table      = 'categoria';
    protected $primaryKey = 'Id';
    public    $timestamps = false;

    /**
     * Categorías activas para el sidebar de filtros.
     */
    public static function activas(): Collection
    {
        return collect(DB::select("
            SELECT Id, categoria, icono
            FROM categoria
            WHERE estado = 1
        "));
    }
}
