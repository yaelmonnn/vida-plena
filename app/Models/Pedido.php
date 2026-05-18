<?php
// app/Models/Pedido.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DetallePedido;
use App\Models\Usuario;

class Pedido extends Model
{
    protected $table      = 'pedido';
    protected $primaryKey = 'Id';
    public    $timestamps = false;

    protected $fillable = [
        'usuario_id', 'estado', 'total',
        'nombre_envio', 'email_envio', 'telefono_envio',
        'calle_envio', 'colonia_envio', 'ciudad_envio', 'cp_envio',
        'stripe_payment_id', 'fr', 'pagado_en',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'Id');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'pedido_id', 'Id');
    }
}
