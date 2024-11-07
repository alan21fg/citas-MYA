<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';
    //
    protected $fillable = [
        'nombre_rol',
    ];

    // Relación con usuarios
    public function usuarios()
    {
        return $this->hasMany(User::class, 'id');
    }

    // Relación con empleados
    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'id');
    }
}
