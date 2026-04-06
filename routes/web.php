<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginUsuarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthUsuario;

Route::get('/', [HomeController::class, 'inicio'])->name('inicio');
Route::get('/producto/{id}/imagenes', [HomeController::class, 'imagenes']);
Route::get('/tienda', [HomeController::class, 'tienda'])->name('tienda');

Route::middleware('guest:usuario')->group(function () {
    Route::get('/login',    [LoginUsuarioController::class, 'mostrarLogin'])->name('login.usuario');
    Route::post('/login',   [LoginUsuarioController::class, 'login']);
    Route::get('/registro', [LoginUsuarioController::class, 'mostrarRegistro'])->name('registro');
    Route::post('/registro',[LoginUsuarioController::class, 'registrar']);
});

Route::get('/verificar-cuenta', [LoginUsuarioController::class, 'verificarCuenta']);

Route::post('/logout', [LoginUsuarioController::class, 'logout'])
    ->name('logout.usuario')
    ->middleware('auth:usuario');

