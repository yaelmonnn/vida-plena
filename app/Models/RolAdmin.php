<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
