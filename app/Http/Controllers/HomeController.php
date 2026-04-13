<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\StoreCategoriaRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Models\Producto;
use App\Models\Seccion;

class HomeController extends Controller
{
    public function inicio(): View {

        return view('inicio/inicio');
    }

    public function contacto(): View {

        return view('contacto/contacto');
    }

    public function nosotros(): View {

        return view('nosotros/nosotros');
    }

    public function imagenes(int $id): JsonResponse
    {
        $imagenes = Producto::imagenes($id);

        return response()->json($imagenes->values());
    }

    public function tienda(): View
    {
        return view('tienda/tienda');
    }




}
