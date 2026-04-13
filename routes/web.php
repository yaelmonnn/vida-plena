<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginUsuarioController;
use App\Http\Controllers\Auth\AdminAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'inicio'])->name('inicio');
Route::get('/producto/{id}/imagenes', [HomeController::class, 'imagenes']);
Route::get('/tienda', [HomeController::class, 'tienda'])->name('tienda');
Route::get('/contacto', [HomeController::class, 'contacto'])->name('contacto');
Route::get('/nosotros', [HomeController::class, 'nosotros'])->name('nosotros');

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

Route::get('/sesion/expirada', [LoginUsuarioController::class, 'sesionExpirada'])
    ->name('sesion.expirada');

Route::prefix('admin')->name('admin.')->group(function () {

    // Rutas públicas
    Route::get('/login',  [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');

    // Rutas protegidas
    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout',   [AdminAuthController::class, 'logout'])->name('logout');
    });

});


