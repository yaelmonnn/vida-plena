<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductGrid extends Component
{
    public $productos;

    public function __construct()
    {
        $productos = DB::select('EXEC pa_traerProductos');

        $page = request()->get('page', 1);
        $perPage = 6;

        $items = collect($productos);

        $this->productos = new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            ['path' => request()->url()]
        );
    }

    public function render()
    {
        return view('components.product-grid');
    }
}
