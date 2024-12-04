<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Ruta pública para el inicio de sesión
Route::post('login', [AuthController::class, 'login'])->name('login');
