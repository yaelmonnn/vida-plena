<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Producto;
use App\Models\Categoria;

class BuscarProductos extends Component
{
    use WithPagination;

    public string $buscar                  = '';
    public bool   $mostrarSugerencias      = false;
    public array  $categoriasSeleccionadas = [];
    public string $tipo                    = '';
    public int    $precioMin               = 0;
    public int    $precioMax               = 0;
    public string $ordenar                 = 'destacados';
    public ?int   $productoDetalle         = null;

    public function mount(): void
    {
        $rango           = Producto::rangoPrecio();
        $this->precioMin = (int) $rango->minimo;
        $this->precioMax = (int) $rango->maximo;
    }

    public function updatedBuscar(): void
    {
        $this->mostrarSugerencias = true;
        $this->resetPage();
    }

    public function abrirSugerencias(): void  { $this->mostrarSugerencias = true; }
    public function cerrarSugerencias(): void { $this->mostrarSugerencias = false; }

    public function updatedCategoriasSeleccionadas(): void { $this->resetPage(); }
    public function updatedTipo(): void                    { $this->resetPage(); }
    public function updatedPrecioMin(): void               { $this->resetPage(); }
    public function updatedPrecioMax(): void               { $this->resetPage(); }
    public function updatedOrdenar(): void                 { $this->resetPage(); }

    public function seleccionarSugerencia(int $id): void
    {
        $nombre = Producto::nombrePorId($id);
        if ($nombre) $this->buscar = $nombre;
        $this->mostrarSugerencias = false;
        $this->resetPage();
    }

    public function abrirDetalle(int $id): void { $this->productoDetalle = $id; }
    public function cerrarDetalle(): void        { $this->productoDetalle = null; }

    public function limpiarFiltros(): void
    {
        $this->buscar                  = '';
        $this->mostrarSugerencias      = false;
        $this->categoriasSeleccionadas = [];
        $this->tipo                    = '';
        $this->ordenar                 = 'destacados';
        $rango           = Producto::rangoPrecio();
        $this->precioMin = (int) $rango->minimo;
        $this->precioMax = (int) $rango->maximo;
        $this->resetPage();
    }

    public function render()
    {
        $rango       = Producto::rangoPrecio();
        $rangoAbsMin = (int) $rango->minimo;
        $rangoAbsMax = (int) $rango->maximo;

        $categorias = Categoria::activas();

        $orderSQL = match ($this->ordenar) {
            'precio_asc'  => 'p.precio ASC',
            'precio_desc' => 'p.precio DESC',
            'nombre'      => 'p.nombre ASC',
            default       => 'p.calificacion DESC, p.nombre ASC',
        };

        $todos = Producto::destacados(
            $this->buscar,
            $this->precioMin,
            $this->precioMax,
            $this->tipo,
            $orderSQL
        );

        if (!empty($this->categoriasSeleccionadas)) {
            $todos = $todos->whereIn('categoria', $this->categoriasSeleccionadas);
        }

        $perPage  = 6;
        $page     = $this->getPage();
        $total    = $todos->count();
        $productos = new \Illuminate\Pagination\LengthAwarePaginator(
            $todos->forPage($page, $perPage),
            $total,
            $perPage,
            $page,
            ['path' => request()->url()]
        );

        if (!$this->mostrarSugerencias) {
            $sugerencias = collect();
        } else {
            if (strlen($this->buscar) < 2) {
                $sugerencias = Producto::query()
                    ->join('categoria', 'categoria.id', '=', 'producto.categoria_id')
                    ->inRandomOrder()
                    ->limit(3)
                    ->get(['producto.Id', 'producto.nombre', 'categoria.categoria']);
            } else {
                $sugerencias = collect(Producto::sugerencias($this->buscar));
            }
        }

        $detalleProducto  = null;
        $detalleImagenes  = collect();
        $detalleEspecifs  = collect();
        $detalleOpiniones = collect();

        if ($this->productoDetalle) {
            $detalleProducto  = Producto::detalle($this->productoDetalle);
            $detalleImagenes  = Producto::imagenes($this->productoDetalle);
            $detalleEspecifs  = Producto::especificaciones($this->productoDetalle);
            $detalleOpiniones = Producto::opiniones($this->productoDetalle);
        }

        return view('livewire.buscar-productos', [
            'productos'        => $productos,
            'sugerencias'      => $sugerencias,
            'categorias'       => $categorias,
            'rangoAbsMin'      => $rangoAbsMin,
            'rangoAbsMax'      => $rangoAbsMax,
            'detalleProducto'  => $detalleProducto,
            'detalleImagenes'  => $detalleImagenes,
            'detalleEspecifs'  => $detalleEspecifs,
            'detalleOpiniones' => $detalleOpiniones,
        ]);
    }
}
