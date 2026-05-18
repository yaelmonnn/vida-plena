<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deseo extends Model
{
    protected $table      = 'deseos';
    protected $primaryKey = 'Id';
    public    $timestamps = false;

    protected $fillable = ['usuario_id', 'producto_id', 'agregado_en'];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'Id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'Id');
    }
}
