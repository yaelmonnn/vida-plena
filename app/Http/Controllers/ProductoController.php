<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\StoreCategoriaRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Seccion;
use App\Models\RolAdmin;

class ProductoController extends Controller
{
    public function formProductos(): View {

        $categorias = Categoria::activas();

        $estados = collect(DB::select("
            SELECT Id, estado_nombre
            FROM estado_producto
            WHERE estado = 1
        "));

        $modulos = RolAdmin::modulosPorUsuario(session('admin_id'));

        return view('admin.productos.form', [
            'categorias' => $categorias,
            'estados' => $estados,
            'modulos' => $modulos
        ]);
    }




}
