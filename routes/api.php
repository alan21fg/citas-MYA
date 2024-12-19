<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    InventarioController,
    ProductoController,
    ServicioController,
    CitaController,
    ClienteController,
    EmpleadoController,
    UserController,
    RolController,
    ReporteController,
    AuthController
};

// Rutas protegidas por JWT
Route::middleware(['jwt.auth'])->group(function () {
    // Cerrar sesión
    Route::post('logout', [AuthController::class, 'logout']);

    // Recursos protegidos
    Route::apiResource('inventarios', InventarioController::class);
    Route::apiResource('productos', ProductoController::class);
    Route::apiResource('servicios', ServicioController::class);
    Route::apiResource('citas', CitaController::class);
    Route::apiResource('clientes', ClienteController::class);
    Route::apiResource('empleados', EmpleadoController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('roles', RolController::class);

    // Rutas de reportes
    Route::get('reportes/estadisticas-mes', [ReporteController::class, 'reporteEstadisticasMes']);
    Route::get('reportes/citas-proximas', [ReporteController::class, 'reporteCitasProximas']);
    Route::get('reportes/inventario-critico', [ReporteController::class, 'reporteInventarioCritico']);
    Route::get('reportes/citas-por-mes', [ReporteController::class, 'reporteCitasPorMes']);

    Route::delete('servicios/{idServicio}/productos/{idProducto}', [ServicioController::class, 'eliminarProductoDeServicio']);
    Route::post('citas/verificar-disponibilidad', [CitaController::class, 'verificarDisponibilidad']);
    Route::get('citas/cliente/{idCliente}', [CitaController::class, 'obtenerCitasPorCliente']);
});
