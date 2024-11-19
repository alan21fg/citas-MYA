<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cliente');
            //$table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('cascade');
            $table->unsignedBigInteger('id_empleado');
            //$table->foreign('id_empleado')->references('id')->on('empleados')->onDelete('cascade');
            $table->unsignedBigInteger('id_servicio');
            //$table->foreign('id_servicio')->references('id')->on('servicios')->onDelete('cascade');
            $table->date('fecha')->nullable(false);
            $table->time('hora')->nullable(false);
            $table->enum('estado', ['Cita Pendiente', 'Cita Concluida', 'Cita Cancelada', 'Cita Agendada']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
