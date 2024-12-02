<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sanctum/csrf-cookie', function () {
    return response()->noContent();
});

// Ruta pública para el inicio de sesión
Route::post('login', [AuthController::class, 'login'])->name('login');
