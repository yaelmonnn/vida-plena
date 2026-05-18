<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagenProducto extends Model
{
    protected $table = 'imagenes_producto';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'producto_id',
        'ruta',
        'alt_text',
        'orden'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'Id');
    }
}
