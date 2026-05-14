<?php

// app/Models/Carrito.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    protected $table      = 'carrito';
    protected $primaryKey = 'Id';
    public    $timestamps = false;

    protected $fillable = [
        'usuario_id', 'producto_id', 'cantidad', 'agregado_en',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'Id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'Id');
    }

}
