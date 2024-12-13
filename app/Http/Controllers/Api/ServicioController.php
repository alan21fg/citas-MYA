<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;
use App\Http\Requests\StoreServicioRequest;

class ServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servicios = Servicio::with('productos')->get();
        return response()->json($servicios, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServicioRequest $request)
    {
        $servicio = Servicio::create($request->validated());

        // Asociar productos con cantidades (asume que el request tiene un array 'productos' con id_producto y cantidad)
        if ($request->has('productos') && count($request->productos) > 0) {
            $productos = [];
            foreach ($request->productos as $producto) {
                if (isset($producto['id_producto']) && isset($producto['cantidad'])) {
                    $productos[$producto['id_producto']] = ['cantidad' => $producto['cantidad']];
                }
            }
            $servicio->productos()->sync($productos);
        }


        return response()->json($servicio->load('productos'), 201); // Devuelve el servicio con los productos asociados
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $servicio = Servicio::with([
            'productos' => function ($query) {
                $query->select('productos.*', 'producto_servicio.cantidad as cantidad'); // Incluimos la cantidad del pivote
            }
        ])->findOrFail($id);

        return response()->json($servicio, 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(StoreServicioRequest $request, string $id)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->update($request->validated());

        // Actualizar productos asociados con cantidades
        if ($request->has('productos') && count($request->productos) > 0) {
            $productos = [];
            foreach ($request->productos as $producto) {
                if (isset($producto['id_producto']) && isset($producto['cantidad'])) {
                    $productos[$producto['id_producto']] = ['cantidad' => $producto['cantidad']];
                }
            }
            $servicio->productos()->sync($productos);
        }


        return response()->json($servicio->load('productos'), 200); // Devuelve el servicio con los productos actualizados
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->delete();

        return response()->json(null, 204);
    }

    /**
     * Eliminar un producto de un servicio específico.
     */
    public function eliminarProductoDeServicio(string $idServicio, string $idProducto)
    {
        // Buscar el servicio
        $servicio = Servicio::findOrFail($idServicio);

        // Eliminar la relación en la tabla pivote (producto_servicio)
        $servicio->productos()->detach($idProducto);

        // Devolver una respuesta exitosa
        return response()->json(['message' => 'Producto eliminado de servicio'], 200);
    }

}
