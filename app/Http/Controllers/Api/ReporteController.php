<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Inventario;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    // Reporte de estado del inventario
    public function reporteInventario()
    {
        $inventarios = Inventario::all();
        return response()->json($inventarios, 200);
    }

    // Reporte de ingresos diarios
    public function reporteIngresosDiarios()
    {
        $fechaHoy = Carbon::today();  // Fecha de hoy
        $citasHoy = Cita::whereDate('fecha', $fechaHoy)->get();

        // Calcular ingresos del día sumando los precios de los servicios realizados
        $ingresosTotales = $citasHoy->sum(function($cita) {
            return $cita->servicio->precio;
        });

        return response()->json([
            'fecha' => $fechaHoy->toDateString(),
            'ingresosTotales' => $ingresosTotales,
            'citas' => $citasHoy,
        ], 200);
    }

    // Reporte del rendimiento general (ejemplo: ingresos mensuales)
    public function reporteRendimientoGeneral()
    {
        $fechaInicioMes = Carbon::now()->startOfMonth();
        $fechaFinMes = Carbon::now()->endOfMonth();

        $citasMes = Cita::whereBetween('fecha', [$fechaInicioMes, $fechaFinMes])->get();
        $ingresosMensuales = $citasMes->sum(function($cita) {
            return $cita->servicio->precio;
        });

        return response()->json([
            'mes' => Carbon::now()->format('F Y'),
            'ingresosMensuales' => $ingresosMensuales,
            'totalCitas' => $citasMes->count(),
        ], 200);
    }
}
