<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Administrador extends Authenticatable
{
    protected $table = 'administrador'; // ajusta al nombre real de tu tabla

    protected $primaryKey = 'Id'; // ← explícito
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'rol_id', 'nombre', 'apellido', 'email',
        'password', 'avatar', 'ultimo_acceso', 'activo',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function rol()
    {
        return $this->belongsTo(RolAdmin::class, 'rol_id', 'Id'); // ← FK, PK del rol
    }
}
