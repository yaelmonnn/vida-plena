<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class Seccion extends Model
{
    protected $table      = 'cat_secciones';
    protected $primaryKey = 'Id';
    public    $timestamps = false;

    public static function navbar(): Collection
    {
        return self::where('activo', 1)
            ->whereIn('Id', [1,2,3,4])
            ->get(['Id','seccion', 'ruta']);
    }
}
