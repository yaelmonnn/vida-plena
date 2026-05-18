<?php
// app/Models/DetallePedido.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pedido;
use App\Models\Producto;

class DetallePedido extends Model
{
    protected $table      = 'detalle_pedido';
    protected $primaryKey = 'Id';
    public    $timestamps = false;

    protected $fillable = [
        'pedido_id', 'producto_id', 'nombre_producto', 'tipo_producto',
        'precio_unitario', 'cantidad', 'subtotal', 'fecha_servicio',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id', 'Id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'Id');
    }
}
