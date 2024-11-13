<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    //
    protected $fillable = [
        'nombre_producto',
        'descripcion',
        'precio_unitario',
        'tipo',
        'fecha_caducidad',
    ];

    // Relación muchos a muchos con servicios
    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'producto_servicio')->withTimestamps();
    }
}
