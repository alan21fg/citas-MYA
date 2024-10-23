<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventario;
use Illuminate\Http\Request;
use App\Http\Requests\StoreInventarioRequest;

class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventarios = Inventario::with('producto')->get();
        return response()->json($inventarios, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInventarioRequest $request)
    {
        $inventario = Inventario::create($request->validated());
        return response()->json($inventario, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $inventario = Inventario::with('producto')->findOrFail($id);
        return response()->json($inventario, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreInventarioRequest $request, string $id)
    {
        $inventario = Inventario::findOrFail($id);

        $inventario->update($request->validated());

        return response()->json($inventario, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $inventario = Inventario::findOrFail($id);
        $inventario->delete();

        return response()->json(null, 204);
    }

}
