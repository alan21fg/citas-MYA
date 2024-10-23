<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRolRequest;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Rol::all();
        return response()->json($roles, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRolRequest $request)
    {
        $rol = Rol::create($request->validated());
        return response()->json($rol, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rol = Rol::findOrFail($id);
        return response()->json($rol, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRolRequest $request, string $id)
    {
        $rol = Rol::findOrFail($id);
        $rol->update($request->validated());

        return response()->json($rol, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rol = Rol::findOrFail($id);
        $rol->delete();

        return response()->json(null, 204);
    }
}
