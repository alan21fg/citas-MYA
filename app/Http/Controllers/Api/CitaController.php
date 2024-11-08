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
        $originalEstado = $cita->estado; // Guarda el estado actual de la cita

        // Actualiza los datos de la cita
        $cita->update($request->validated());

        // Si el estado ha cambiado a "completado", actualiza el inventario
        if ($originalEstado !== 'completado' && $cita->estado === 'Completada') {
            $this->actualizarInventario($cita);
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

        foreach ($servicio->productos as $producto) {
            // Busca el inventario del producto
            $inventario = Inventario::where('id_producto', $producto->id)->first();

            if ($inventario) {
                // Calcula la nueva cantidad disponible
                $nuevaCantidad = $inventario->cantidad_disponible - $producto->pivot->cantidad;

                // Evita valores negativos
                if ($nuevaCantidad < 0) {
                    return response()->json([
                        'error' => "No hay suficiente cantidad de {$producto->nombre_producto} en el inventario."
                    ], 400);
                }

                // Actualiza el inventario
                $inventario->update(['cantidad_disponible' => $nuevaCantidad]);
            }
        }
    }
}
