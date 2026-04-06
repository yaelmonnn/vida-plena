<?php
// ── app/Models/Usuario.php ────────────────────────────────────

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Usuario extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table      = 'usuario';
    protected $primaryKey = 'Id';
    public    $timestamps = false;

    protected $fillable = [
        'nombre', 'apellido', 'email', 'password',
        'telefono', 'fecha_nacimiento',
        'calle', 'colonia', 'ciudad', 'estado_dir', 'cp',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'activo'            => 'boolean',
        ];
    }

    /** Nombre completo */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    /** Registrar último acceso */
    public function registrarAcceso(): void
    {
        DB::statement(
            "UPDATE usuario SET ultimo_acceso = GETDATE() WHERE Id = ?",
            [$this->Id]
        );
    }

    public function getJWTIdentifier()
    {
        return $this->getKey(); // normalmente el ID
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
