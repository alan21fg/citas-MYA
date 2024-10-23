<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InventarioController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\ServicioController;
use App\Http\Controllers\Api\CitaController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\EmpleadoController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RolController;
use App\Http\Controllers\Api\ReporteController;

Route::apiResource('inventarios', InventarioController::class);
Route::apiResource('productos', ProductoController::class);
Route::apiResource('servicios', ServicioController::class);
Route::apiResource('citas', CitaController::class);
Route::apiResource('clientes', ClienteController::class);
Route::apiResource('empleados', EmpleadoController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('roles', RolController::class);
Route::get('reportes/inventario', [ReporteController::class, 'reporteInventario']);
Route::get('reportes/ingresos-diarios', [ReporteController::class, 'reporteIngresosDiarios']);
Route::get('reportes/rendimiento-general', [ReporteController::class, 'reporteRendimientoGeneral']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
