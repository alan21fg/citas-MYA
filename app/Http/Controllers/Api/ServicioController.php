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
    public function store(StoreServicioRequest  $request)
    {
        $servicio = Servicio::create($request->validated());
        return response()->json($servicio, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $servicio = Servicio::findOrFail($id);
        return response()->json($servicio, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreServicioRequest  $request, string $id)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->update($request->validated());

        return response()->json($servicio, 200);
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
}
