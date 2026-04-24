<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public $modulos;

    public function __construct($modulos)
    {
        $this->modulos = $modulos;
    }

    public function render()
    {
        return view('components.admin.sidebar');
    }
}
