<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InventarioController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\ServicioController;

Route::apiResource('inventarios', InventarioController::class);
Route::apiResource('productos', ProductoController::class);
Route::apiResource('servicios', ServicioController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
