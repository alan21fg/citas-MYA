<?php

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
use App\Http\Controllers\Api\AuthController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

// Ruta pública para el inicio de sesión
Route::post('login', [AuthController::class, 'login']);

// Rutas protegidas con auth:sanctum
Route::middleware('auth:sanctum', EnsureFrontendRequestsAreStateful::class)->group(function () {
    Route::apiResource('inventarios', InventarioController::class);
    Route::apiResource('productos', ProductoController::class);
    Route::apiResource('servicios', ServicioController::class);
    Route::apiResource('citas', CitaController::class);
    Route::apiResource('clientes', ClienteController::class);
    Route::apiResource('empleados', EmpleadoController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('roles', RolController::class);

    Route::get('reportes/estadisticas-mes', [ReporteController::class, 'reporteEstadisticasMes']);
    Route::get('reportes/citas-proximas', [ReporteController::class, 'reporteCitasProximas']);
    Route::get('reportes/inventario-critico', [ReporteController::class, 'reporteInventarioCritico']);
    Route::get('reportes/citas-por-mes', [ReporteController::class, 'reporteCitasPorMes']);
});
