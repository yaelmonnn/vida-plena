<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Categoria;

class Filtros extends Component
{
    public $categorias;

    public function __construct()
    {
        $this->categorias = Categoria::where('estado', 1)->get();
    }

    public function render()
    {
        return view('components.filtros');
    }
}
