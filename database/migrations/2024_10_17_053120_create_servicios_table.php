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
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_servicio')->nullable(false);
            $table->string('descripcion')->nullable(false);
            $table->double('precio', 8, 2)->nullable(false);
            $table->integer('duracion')->nullable(false);
            $table->timestamps();
        });

        Schema::create('producto_servicio', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_servicio');
            //$table->foreign('id_servicio')->references('id')->on('servicios')->onDelete('cascade');
            $table->unsignedBigInteger('id_producto');
            //$table->foreign('id_producto')->references('id')->on('productos')->onDelete('cascade');
            $table->integer('cantidad')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
        Schema::dropIfExists('producto_servicio');
    }
};
