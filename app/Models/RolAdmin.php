<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RolAdmin extends Model
{
    protected $table = 'rol_admin';
    protected $primaryKey = 'Id'; // ← mayúscula igual que en BD
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'descripcion', 'puede_productos', 'puede_servicios',
        'puede_categorias', 'puede_usuarios', 'puede_admins',
        'puede_pedidos', 'puede_reportes', 'activo',
    ];

    public static function modulosPorUsuario($usuarioId)
    {
        return DB::table('cat_modulos as cm')
            ->join('conf_modulo as cfm', 'cfm.modulo_id', '=', 'cm.Id')
            ->where('cfm.usuario_id', $usuarioId)
            ->select('cm.Id', 'cm.modulo', 'cm.categoria', 'cm.icono', 'cm.ruta')
            ->get();
    }
}
