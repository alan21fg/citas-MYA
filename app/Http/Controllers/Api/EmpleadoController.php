<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEmpleadoRequest;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empleados = Empleado::with('usuario', 'rol')->get();
        return response()->json($empleados, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmpleadoRequest $request)
    {
        $empleado = Empleado::create($request->validated());
        return response()->json($empleado, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $empleado = Empleado::with('usuario','rol')->findOrFail($id);
        return response()->json($empleado, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreEmpleadoRequest $request, string $id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->update($request->validated());

        return response()->json($empleado, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->delete();

        return response()->json(null, 204);
    }
}
