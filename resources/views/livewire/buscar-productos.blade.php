<?php

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

new class extends Component
{
    use WithPagination;

    public $buscar = "";
    public $mostrarSugerencias = false;
    public $categoriasSeleccionadas = [];
    public $precioMax = 10000;

    public function updatedBuscar()
    {
        $this->mostrarSugerencias = true;
        $this->resetPage();
    }

    public function updatedCategoriasSeleccionadas()
    {
        $this->resetPage();
    }

    public function updatedPrecioMax()
    {
        $this->resetPage();
    }

    public function seleccionarProducto($id)
    {
        $producto = DB::selectOne("SELECT nombre FROM producto WHERE Id = ?", [$id]);
        $this->buscar = $producto->nombre;
        $this->mostrarSugerencias = false;
        $this->resetPage();
    }

    public function render()
    {
        $categorias = DB::select("SELECT Id, categoria, icono FROM categoria WHERE estado = 1");

        $todos = DB::select("
            SELECT
                p.Id,
                c.categoria,
                ep.estado_nombre,
                p.nombre,
                p.calificacion,
                p.descripcion,
                p.precio,
                p.imagen,
                p.cantidad_disponible
            FROM producto p
            INNER JOIN categoria c ON c.Id = p.categoria_id
            INNER JOIN estado_producto ep ON ep.Id = p.estado_id
            WHERE p.estado = 1
            AND (
                ? = '' OR
                p.nombre COLLATE Latin1_General_CI_AI LIKE '%' + ? + '%' OR
                c.categoria COLLATE Latin1_General_CI_AI LIKE '%' + ? + '%'
            )
            AND p.precio <= ?
        ", [
            $this->buscar, $this->buscar, $this->buscar,
            $this->precioMax,
        ]);

        $todos = collect($todos);

        if (!empty($this->categoriasSeleccionadas)) {
            $todos = $todos->whereIn('categoria', $this->categoriasSeleccionadas);
        }


        $perPage = 6;
        $page = $this->getPage();
        $total = $todos->count();

        $productos = new \Illuminate\Pagination\LengthAwarePaginator(
            $todos->forPage($page, $perPage),
            $total,
            $perPage,
            $page,
            ['path' => request()->url()]
        );


        $sugerencias = $todos->take(5);

        return view('livewire.buscar-productos', [
            'productos'   => $productos,
            'sugerencias' => $sugerencias,
            'categorias'  => collect($categorias),
        ]);
    }
};
?>

<section class="max-w-7xl mx-auto px-6 py-12 reveal">

    <div class="grid lg:grid-cols-4 gap-10">

        <!-- SIDEBAR FILTROS -->
        <div class="bg-white p-6 rounded-3xl shadow-xl space-y-8 h-fit text-base">

            <!-- Buscar -->
            <div>
                <label class="font-semibold text-lg">Buscar</label>
                <input
                    type="text"
                    wire:model.live="buscar"
                    placeholder="Ej. barandal, reloj..."
                    class="mt-2 w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#E48F62] outline-none"
                >

                @if($buscar && $mostrarSugerencias)
                <div class="bg-white border mt-2 rounded-xl shadow">
                    @foreach($sugerencias as $s)
                    <div
                        wire:click="seleccionarProducto({{ $s->Id }})"
                        class="px-4 py-2 cursor-pointer">
                        {{ $s->nombre }}
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Categoría -->
            <div>
                <label class="font-semibold text-lg">Categoría</label>
                <div class="mt-3 space-y-3 text-base">
                    @foreach ($categorias as $c)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="checkbox"
                                wire:model.live="categoriasSeleccionadas"
                                value="{{ $c->categoria }}"
                            >
                            <i class="{{ $c->icono }}"></i> {{ $c->categoria }}
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Precio -->
            <div>
                <label class="font-semibold text-lg">Rango de precio</label>
                <input
                    type="range"
                    min="0"
                    max="10000"
                    wire:model.live="precioMax"
                    oninput="document.getElementById('precioLabel').textContent = '$' + Number(this.value).toLocaleString()"
                    class="w-full mt-3 accent-[#E28987]"
                >
                <div class="flex justify-between text-sm text-gray-500">
                    <span>$0</span>
                    <span id="precioLabel">${{ number_format($precioMax, 0) }}</span>
                </div>
            </div>

        </div>

        <!-- PRODUCTOS -->
        <div class="lg:col-span-3">

            @if($productos->total() == 0)
                <div class="text-center text-gray-500 py-20">
                    No se encontraron productos.
                </div>
            @endif

            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-8">
                @foreach ($productos as $p)
                    <x-product-card
                        titulo="{{ $p->nombre }}"
                        precio="{{ $p->precio }}"
                        imagen="{{ $p->imagen }}"
                        categoria="{{ $p->categoria }}"
                        estado="{{ $p->estado_nombre }}"
                        rating="{{ $p->calificacion }}"
                        descripcion="{{ $p->descripcion }}"
                    />
                @endforeach
            </div>

            <!-- PAGINADOR -->
            @if($productos->hasPages())
            <div class="mt-10 flex justify-center gap-2">
                {{-- Anterior --}}
                @if($productos->onFirstPage())
                    <span class="px-4 py-2 rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed">←</span>
                @else
                    <button wire:click="previousPage" class="px-4 py-2 rounded-xl bg-white border hover:bg-[#E48F62] hover:text-white transition">←</button>
                @endif

                {{-- Números --}}
                @foreach($productos->getUrlRange(1, $productos->lastPage()) as $page => $url)
                    @if($page == $productos->currentPage())
                        <span class="px-4 py-2 rounded-xl bg-[#E48F62] text-white font-bold">{{ $page }}</span>
                    @else
                        <button wire:click="gotoPage({{ $page }})" class="px-4 py-2 rounded-xl bg-white border hover:bg-[#E48F62] hover:text-white transition">{{ $page }}</button>
                    @endif
                @endforeach

                {{-- Siguiente --}}
                @if($productos->hasMorePages())
                    <button wire:click="nextPage" class="px-4 py-2 rounded-xl bg-white border hover:bg-[#E48F62] hover:text-white transition">→</button>
                @else
                    <span class="px-4 py-2 rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed">→</span>
                @endif
            </div>
            @endif

        </div>

    </div>

</section>
