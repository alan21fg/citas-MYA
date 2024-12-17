<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_producto');
            //$table->foreign('id_producto')->references('id')->on('productos')->onDelete('cascade');
            $table->string('descripcion');
            $table->integer('cantidad_disponible')->unsigned()->nullable(false);
            $table->integer('punto_reorden')->unsigned()->nullable(false);
            $table->double('precio_compra', 8, 2)->nullable(false);
            $table->double('precio_venta', 8, 2)->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};
