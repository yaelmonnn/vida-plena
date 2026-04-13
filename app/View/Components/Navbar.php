<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Seccion;

class Navbar extends Component
{
    public $secciones;

    public function __construct()
    {
        $this->secciones = Seccion::navbar();
    }

    public function render()
    {
        return view('components.navbar');
    }
}
