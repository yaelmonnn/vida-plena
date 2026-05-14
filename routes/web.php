<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginUsuarioController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DeseoController;
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

Route::middleware('auth:usuario')->group(function () {
    Route::get('/carrito',              [CarritoController::class, 'index'])->name('carrito');
    Route::post('/carrito/agregar',     [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::delete('/carrito/{id}',      [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
    Route::patch('/carrito/{id}/cantidad', [CarritoController::class, 'actualizarCantidad'])->name('carrito.cantidad');
    Route::get('/mis-deseos',                [DeseoController::class, 'index'])->name('deseos');
    Route::post('/deseos/toggle',            [DeseoController::class, 'toggle'])->name('deseos.toggle');
    Route::delete('/deseos/{productoId}',    [DeseoController::class, 'quitar'])->name('deseos.quitar');
    Route::get('/deseos/mis-ids',            [DeseoController::class, 'misIds'])->name('deseos.ids');
});

Route::post('/logout', [LoginUsuarioController::class, 'logout'])
    ->name('logout.usuario')
    ->middleware('auth:usuario');

Route::get('/sesion/expirada', [LoginUsuarioController::class, 'sesionExpirada'])
    ->name('sesion.expirada');

Route::post('/reenviar-verificacion', [LoginUsuarioController::class, 'reenviarVerificacion'])->name('reenviar.verificacion');


Route::prefix('admin')->name('admin.')->group(function () {

    // Rutas públicas
    Route::get('/login',  [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');

    // Rutas protegidas
    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');

        Route::get('/productos', [ProductoController::class, 'formProductos'])->name('productos');
        Route::get('/servicios', [AdminAuthController::class, 'dashboard'])->name('servicios');
        Route::get('/categorias', [AdminAuthController::class, 'dashboard'])->name('categorias');
        Route::get('/pedidos', [AdminAuthController::class, 'dashboard'])->name('pedidos');
        Route::get('/clientes', [AdminAuthController::class, 'dashboard'])->name('clientes');
        Route::get('/usuarios', [AdminAuthController::class, 'dashboard'])->name('usuarios');
        Route::get('/reportes', [AdminAuthController::class, 'dashboard'])->name('reportes');
        Route::get('/productos/{id}/imagenes', [ProductoController::class, 'imagenes'])->name('productos.imagenes');
        Route::post('/productos/{id}/imagenes',         [ProductoController::class, 'agregarImagenes'])->name('productos.imagenes.agregar');
        Route::delete('/productos/imagenes/{imagenId}', [ProductoController::class, 'eliminarImagen'])->name('productos.imagenes.eliminar');
        Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
        Route::put('/productos/{id}',    [ProductoController::class, 'update'])->name('productos.update');
        Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');


        Route::get('/servicios',                        [ProductoController::class, 'formServicios'])->name('servicios');
        Route::get('/servicios/{id}/imagenes',          [ProductoController::class, 'imagenes'])->name('servicios.imagenes');
        Route::post('/servicios/{id}/imagenes',         [ProductoController::class, 'agregarImagenes'])->name('servicios.imagenes.agregar');
        Route::delete('/servicios/imagenes/{imagenId}', [ProductoController::class, 'eliminarImagen'])->name('servicios.imagenes.eliminar');
        Route::post('/servicios',                       [ProductoController::class, 'storeServicio'])->name('servicios.store');
        Route::put('/servicios/{id}',                   [ProductoController::class, 'updateServicio'])->name('servicios.update');
        Route::delete('/servicios/{id}',                [ProductoController::class, 'destroyServicio'])->name('servicios.destroy');

        Route::get('/categorias',         [CategoriaController::class, 'index'])->name('categorias');
        Route::post('/categorias',        [CategoriaController::class, 'store'])->name('categorias.store');
        Route::put('/categorias/{id}',    [CategoriaController::class, 'update'])->name('categorias.update');
        Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');



        Route::post('/logout',   [AdminAuthController::class, 'logout'])->name('logout');
    });

});


