<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cita;
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
        $cita->update($request->validated());

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
}
