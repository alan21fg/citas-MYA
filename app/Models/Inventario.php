<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    //
    protected $fillable = [
        'id_producto',
        'cantidad_disponible',
        'punto_reorden',
    ];

    // Relación con productos
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id');
    }
}
