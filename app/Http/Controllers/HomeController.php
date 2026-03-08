<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;

class HomeController extends Controller
{
    public function inicio(): View {
        return view('inicio/inicio');
    }



}
