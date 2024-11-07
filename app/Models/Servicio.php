<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    //
    protected $fillable = [
        'nombre_servicio',
        'descripcion',
        'precio',
        'duracion',
    ];

    // Relación muchos a muchos con productos
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_servicio')->withTimestamps();
    }
}
