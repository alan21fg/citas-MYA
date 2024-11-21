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
}
