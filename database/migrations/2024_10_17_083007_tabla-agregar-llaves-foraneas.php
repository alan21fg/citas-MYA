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
        // Agregar las claves foráneas a la tabla empleados
        Schema::table('empleados', function (Blueprint $table) {
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_rol')->references('id')->on('roles')->onDelete('cascade');
        });

        // Agregar las claves foráneas a la tabla usuarios
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('id_rol')->references('id')->on('roles')->onDelete('cascade');
        });

        // Agregar las claves foráneas a la tabla inventarios
        Schema::table('inventarios', function (Blueprint $table) {
            $table->foreign('id_producto')->references('id')->on('productos')->onDelete('cascade');
        });

        // Agregar las claves foráneas a la tabla citas
        Schema::table('citas', function (Blueprint $table) {
            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('id_empleado')->references('id')->on('empleados')->onDelete('cascade');
            $table->foreign('id_servicio')->references('id')->on('servicios')->onDelete('cascade');
        });

        // Agregar claves foráneas a la tabla pivote producto_servicio
        Schema::table('producto_servicio', function (Blueprint $table) {
            $table->foreign('id_servicio')->references('id')->on('servicios')->onDelete('cascade');
            $table->foreign('id_producto')->references('id')->on('productos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('producto_servicio', function (Blueprint $table) {
            $table->dropForeign(['id_servicio']);
            $table->dropForeign(['id_producto']);
        });

        Schema::table('citas', function (Blueprint $table) {
            $table->dropForeign(['id_cliente']);
            $table->dropForeign(['id_empleado']);
            $table->dropForeign(['id_servicio']);
        });

        Schema::table('inventarios', function (Blueprint $table) {
            $table->dropForeign(['id_producto']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_rol']);
        });

        Schema::table('empleados', function (Blueprint $table) {
            $table->dropForeign(['id_usuario']);
            $table->dropForeign(['id_rol']);
        });
    }
};
