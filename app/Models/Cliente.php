<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    //
    protected $fillable = [
        'nombre',
        'apellido',
        'telefono',
    ];

    // Definir la relación con citas
    public function citas()
    {
        return $this->hasMany(Cita::class);
    }
}
