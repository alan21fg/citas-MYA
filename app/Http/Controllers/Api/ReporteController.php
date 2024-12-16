<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Inventario;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    // Reporte de estadísticas generales del mes
    public function reporteEstadisticasMes()
    {
        $fechaInicioMes = Carbon::now()->startOfMonth();
        $fechaFinMes = Carbon::now()->endOfMonth();

        $citasMes = Cita::whereBetween('fecha', [$fechaInicioMes, $fechaFinMes])
            ->where('estado', 'Cita Concluida') // Solo incluye citas concluidas
            ->get();

        $ingresosMensuales = $citasMes->sum(fn($cita) => $cita->servicio->precio);

        return response()->json([
            'mes' => Carbon::now()->format('F Y'),
            'ingresosMensuales' => $ingresosMensuales,
            'totalCitas' => $citasMes->count(),
        ], 200);
    }


    // Reporte de citas próximas (siguientes tres días)
    public function reporteCitasProximas()
    {
        $fechaHoy = Carbon::today();
        $fechaLimite = $fechaHoy->copy()->addDays(3);

        $citasProximas = Cita::whereBetween('fecha', [$fechaHoy, $fechaLimite])
            ->with(['cliente', 'servicio'])
            ->get();

        return response()->json($citasProximas, 200);
    }

    // Reporte de inventario crítico (productos con stock bajo el punto de reorden)
    public function reporteInventarioCritico()
    {
        $inventarioCritico = Inventario::whereColumn('cantidad_disponible', '<=', 'punto_reorden')
            ->with('producto')
            ->get();

        return response()->json($inventarioCritico, 200);
    }

    // Reporte de citas por mes para graficar
    public function reporteCitasPorMes()
    {
        $mesActual = Carbon::now()->month;
        $citasPorMes = Cita::selectRaw('MONTH(fecha) as mes, COUNT(*) as total')
            ->where('estado', 'Cita Concluida')
            ->groupBy('mes')
            ->get()
            ->map(fn($item) => [
                'name' => Carbon::create()->month($item->mes)->format('F'),
                'value' => $item->total
            ]);

        return response()->json($citasPorMes, 200);
    }
}
