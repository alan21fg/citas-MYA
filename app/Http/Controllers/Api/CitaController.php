<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Inventario;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCitaRequest;

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $citas = Cita::with(['cliente', 'empleado', 'servicio'])->get();
        return response()->json($citas, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCitaRequest $request)
    {
        $fecha = $request->input('fecha');
        $hora = $request->input('hora');
        $idCliente = $request->input('id_cliente');

        // Verificar si el cliente ya tiene una cita en la misma fecha y hora
        $citaExistente = Cita::where('fecha', $fecha)
            ->where('hora', $hora)
            ->where('id_cliente', $idCliente)
            ->exists();

        if ($citaExistente) {
            return response()->json([
                'error' => 'El cliente ya tiene una cita agendada en la misma fecha y hora.',
            ], 400);
        }

        $cita = Cita::create($request->validated());
        return response()->json($cita, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cita = Cita::with(['cliente', 'empleado', 'servicio'])->findOrFail($id);
        return response()->json($cita, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCitaRequest $request, string $id)
    {
        $cita = Cita::findOrFail($id);
        $originalEstado = $cita->estado;

        // Actualizar los datos de la cita
        $cita->update($request->validated());

        // Si el estado cambia a "Cita Concluida"
        if ($originalEstado !== 'Cita Concluida' && $cita->estado === 'Cita Concluida') {
            $this->actualizarInventario($cita);

            // Actualizar ingresos mensuales
            $reporteController = new ReporteController();
            $reporteController->reporteEstadisticasMes(); // Recalcular los ingresos
        }

        return response()->json($cita, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cita = Cita::findOrFail($id);
        $cita->delete();

        return response()->json(null, 204);
    }

    /**
     * Método privado para actualizar el inventario según los productos del servicio de la cita.
     */
    private function actualizarInventario(Cita $cita)
    {
        $servicio = $cita->servicio()->with('productos')->first();

        if (!$servicio) {
            return response()->json(['error' => 'El servicio no tiene productos asociados.'], 400);
        }

        foreach ($servicio->productos as $producto) {
            // Busca el inventario del producto
            $inventario = Inventario::where('id_producto', $producto->id)->first();

            if ($inventario) {
                $nuevaCantidad = $inventario->cantidad_disponible - $producto->pivot->cantidad;

                if ($nuevaCantidad < 0) {
                    return response()->json([
                        'error' => "No hay suficiente cantidad de {$producto->nombre_producto} en el inventario."
                    ], 400);
                }

                $inventario->update(['cantidad_disponible' => $nuevaCantidad]);
            } else {
                return response()->json([
                    'error' => "El producto {$producto->nombre_producto} no está en el inventario."
                ], 400);
            }
        }
    }

    /**
     * Verifica la disponibilidad de horarios en una fecha específica.
     */
    public function verificarDisponibilidad(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'hora' => 'nullable|date_format:H:i',
            'id_cliente' => 'required|exists:clientes,id',
            'id_servicio' => 'required|exists:servicios,id',
            'id_empleado' => 'nullable|exists:empleados,id',
        ]);

        $fecha = $request->input('fecha');
        $hora = $request->input('hora');
        $idCliente = $request->input('id_cliente');
        $idServicio = $request->input('id_servicio');
        $idEmpleado = $request->input('id_empleado');

        // Validar si el cliente ya tiene una cita en la misma fecha y hora
        if ($hora) {
            $citaCliente = Cita::where('fecha', $fecha)
                ->where('hora', $hora)
                ->where('id_cliente', $idCliente)
                ->exists();

            if ($citaCliente) {
                return response()->json([
                    'error' => 'El cliente ya tiene una cita agendada en la misma fecha y hora.',
                ], 400);
            }
        }

        // Obtener la duración del servicio
        $duracionServicio = \App\Models\Servicio::find($idServicio)->duracion;

        // Obtener citas para la fecha dada
        $citasEnFecha = Cita::where('fecha', $fecha)
            ->when($idEmpleado, function ($query) use ($idEmpleado) {
                return $query->where('id_empleado', $idEmpleado);
            })
            ->get(['hora']);

        // Convertir las horas ocupadas a rangos de minutos
        $horariosOcupados = $citasEnFecha->map(function ($cita) use ($duracionServicio) {
            $inicio = $this->convertirHoraAMinutos($cita->hora);
            $fin = $inicio + $duracionServicio;
            return ['inicio' => $inicio, 'fin' => $fin];
        });

        // Calcular horarios disponibles
        $horariosDisponibles = $this->calcularHorariosDisponibles($horariosOcupados);

        return response()->json([
            'fecha' => $fecha,
            'horarios_disponibles' => $horariosDisponibles,
        ]);
    }


    /**
     * Convierte una hora en formato HH:MM a minutos.
     */
    private function convertirHoraAMinutos(string $hora): int
    {
        [$horas, $minutos] = explode(':', $hora);
        return $horas * 60 + $minutos;
    }

    /**
     * Calcula los horarios disponibles dados los rangos ocupados.
     */
    private function calcularHorariosDisponibles($horariosOcupados)
    {
        $horarioInicio = 10 * 60; // 10:00 AM
        $horarioFin = 18 * 60; // 6:00 PM
        $disponibles = [];

        $inicioDisponible = $horarioInicio;

        foreach ($horariosOcupados->sortBy('inicio') as $rango) {
            if ($inicioDisponible < $rango['inicio']) {
                $disponibles[] = [
                    'inicio' => $inicioDisponible,
                    'fin' => $rango['inicio'],
                ];
            }
            $inicioDisponible = max($inicioDisponible, $rango['fin']);
        }

        if ($inicioDisponible < $horarioFin) {
            $disponibles[] = [
                'inicio' => $inicioDisponible,
                'fin' => $horarioFin,
            ];
        }

        return collect($disponibles)->map(function ($rango) {
            return [
                'inicio' => $this->convertirMinutosAHora($rango['inicio']),
                'fin' => $this->convertirMinutosAHora($rango['fin']),
            ];
        });
    }

    /**
     * Convierte minutos a formato HH:MM.
     */
    private function convertirMinutosAHora(int $minutos): string
    {
        $horas = floor($minutos / 60);
        $mins = $minutos % 60;
        return sprintf('%02d:%02d', $horas, $mins);
    }

    public function obtenerCitasPorCliente($idCliente)
    {
        // Validar que el cliente existe
        if (!\App\Models\Cliente::find($idCliente)) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }

        // Obtener las citas del cliente
        $citas = Cita::where('id_cliente', $idCliente)
            ->orderBy('fecha', 'asc')
            ->orderBy('hora', 'asc')
            ->get(['fecha', 'hora']);

        return response()->json($citas, 200);
    }
}
