<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    //
    protected $fillable = [
        'fecha',
        'hora',
        'estado',
        'id_cliente',
        'id_empleado',
        'id_servicio',
    ];

    protected $dates = ['fecha', 'hora'];


    // Relación con Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación con Empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    // Relación con Servicio
    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }
}
