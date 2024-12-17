<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    //
    protected $fillable = [
        'id_producto',
        'descripcion',
        'cantidad_disponible',
        'punto_reorden',
        'precio_compra',
        'precio_venta',
    ];

    // Relación con productos
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id');
    }
}
