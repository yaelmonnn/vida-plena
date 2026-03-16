<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\StoreCategoriaRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Models\Producto;

class HomeController extends Controller
{
    public function inicio(): View {
        return view('inicio/inicio');
    }

    public function imagenes(int $id): JsonResponse
    {
        $imagenes = Producto::imagenes($id);
 
        return response()->json($imagenes->values());
    }



}
