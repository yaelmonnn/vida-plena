<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Producto;
use App\Models\Categoria;

new class extends Component
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

    // ──────────────────────────────── LIFECYCLE ──

    public function mount(): void
    {
        $rango           = Producto::rangoPrecio();
        $this->precioMin = (int) $rango->minimo;
        $this->precioMax = (int) $rango->maximo;
    }

    // ──────────────────────────────── WATCHERS ───

    public function updatedBuscar(): void
    {
        $this->mostrarSugerencias = strlen($this->buscar) >= 2;
        $this->resetPage();
    }

    public function updatedCategoriasSeleccionadas(): void { $this->resetPage(); }
    public function updatedTipo(): void                    { $this->resetPage(); }
    public function updatedPrecioMin(): void               { $this->resetPage(); }
    public function updatedPrecioMax(): void               { $this->resetPage(); }
    public function updatedOrdenar(): void                 { $this->resetPage(); }

    // ──────────────────────────────── ACTIONS ────

    public function seleccionarSugerencia(int $id): void
    {
        $nombre = Producto::nombrePorId($id);
        if ($nombre) {
            $this->buscar = $nombre;
        }
        $this->mostrarSugerencias = false;
        $this->resetPage();
    }

    public function cerrarSugerencias(): void
    {
        $this->mostrarSugerencias = false;
    }

    public function abrirDetalle(int $id): void
    {
        $this->productoDetalle = $id;
    }

    public function cerrarDetalle(): void
    {
        $this->productoDetalle = null;
    }

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

    // ──────────────────────────────── RENDER ─────

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

        $perPage   = 6;
        $page      = $this->getPage();
        $total     = $todos->count();
        $productos = new \Illuminate\Pagination\LengthAwarePaginator(
            $todos->forPage($page, $perPage),
            $total,
            $perPage,
            $page,
            ['path' => request()->url()]
        );

        $sugerencias = $this->mostrarSugerencias
            ? collect(Producto::sugerencias($this->buscar))
            : collect();

        $detalleProducto = null;
        $detalleImagenes = collect();
        $detalleEspecifs = collect();

        if ($this->productoDetalle) {
            $detalleProducto = Producto::detalle($this->productoDetalle);
            $detalleImagenes = Producto::imagenes($this->productoDetalle);
            $detalleEspecifs = Producto::especificaciones($this->productoDetalle);
        }

        return view('livewire.buscar-productos', [
            'productos'       => $productos,
            'sugerencias'     => $sugerencias,
            'categorias'      => $categorias,
            'rangoAbsMin'     => $rangoAbsMin,
            'rangoAbsMax'     => $rangoAbsMax,
            'detalleProducto' => $detalleProducto,
            'detalleImagenes' => $detalleImagenes,
            'detalleEspecifs' => $detalleEspecifs,
        ]);
    }
};
?>

{{-- ════════════════════════════════════════════════════════
     VISTA  — Productos Destacados
     ════════════════════════════════════════════════════════ --}}
<div>
<section class="max-w-7xl mx-auto px-6 py-16 reveal">

    {{-- ── ENCABEZADO ── --}}
    <div class="text-center mb-12">
        <h2 class="text-4xl md:text-5xl font-extrabold text-gray-800 leading-tight">
            Productos y Servicios <span class="text-[#E48F62]">Destacados</span>
        </h2>
        <p class="mt-3 text-gray-500 max-w-xl mx-auto">
            Los más valorados por nuestra comunidad, seleccionados para ti.
        </p>
    </div>

    <div class="grid lg:grid-cols-4 gap-10">

        {{-- ═══════════════════════ SIDEBAR ══════════════════════ --}}
        <aside class="bg-white p-6 rounded-3xl shadow-xl space-y-8 h-fit text-base lg:sticky lg:top-6">

            {{-- Buscar --}}
            <div class="relative" x-data>
                <label class="font-semibold text-gray-700 text-base"><i class="fa-solid fa-magnifying-glass mr-1"></i> Buscar</label>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="buscar"
                    placeholder="Nombre, descripción..."
                    class="mt-2 w-full border border-gray-200 rounded-xl px-4 py-3 text-sm
                           focus:ring-2 focus:ring-[#E48F62] outline-none transition"
                >
                @if($mostrarSugerencias && $sugerencias->isNotEmpty())
                <div class="absolute z-50 left-0 right-0 bg-white border border-gray-100
                            mt-1 rounded-xl shadow-xl overflow-hidden">
                    @foreach($sugerencias as $s)
                    <button
                        wire:click="seleccionarSugerencia({{ $s->Id }})"
                        class="w-full text-left px-4 py-2.5 hover:bg-[#FFF3EC] text-sm
                               flex items-center gap-2 border-b border-gray-50 last:border-0">
                        <span class="text-[#E48F62] text-xs font-medium">{{ $s->categoria }}</span>
                        <span class="text-gray-700">{{ $s->nombre }}</span>
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Tipo --}}
            <div>
                <label class="font-semibold text-gray-700 text-base"><i class="fa-solid fa-layer-group mr-1"></i> Tipo</label>
                <div class="mt-3 flex gap-2 flex-wrap">
                    @foreach(['' => 'Todos', 'producto' => 'Productos', 'servicio' => 'Servicios'] as $val => $etiqueta)
                    <button
                        wire:click="$set('tipo', '{{ $val }}')"
                        class="px-3 py-1.5 rounded-full text-sm font-medium border transition
                               {{ $tipo === $val
                                    ? 'bg-[#E48F62] text-white border-[#E48F62]'
                                    : 'bg-white text-gray-600 border-gray-200 hover:border-[#E48F62]' }}">
                        {{ $etiqueta }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Categoría --}}
            <div>
                <label class="font-semibold text-gray-700 text-base"><i class="fa-solid fa-folder-open mr-1"></i> Categoría</label>
                <div class="mt-3 space-y-2">
                    @foreach ($categorias as $c)
                    <label class="flex items-center gap-2 cursor-pointer group text-sm">
                        <input
                            type="checkbox"
                            wire:model.live="categoriasSeleccionadas"
                            value="{{ $c->categoria }}"
                            class="accent-[#E48F62] w-4 h-4 rounded"
                        >
                        <i class="{{ $c->icono }} text-[#E48F62] w-4"></i>
                        <span class="text-gray-700 group-hover:text-[#E48F62] transition">
                            {{ $c->categoria }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Rango de precio (inputs numéricos accesibles) --}}
            <div>
                <label class="font-semibold text-gray-700 text-base"><i class="fa-solid fa-dollar-sign mr-1"></i> Rango de precio</label>
                <div class="mt-3 grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs text-gray-500 mb-1 block">Mínimo</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                            <input
                                type="number"
                                wire:model.live.debounce.400ms="precioMin"
                                min="{{ $rangoAbsMin }}"
                                max="{{ $rangoAbsMax }}"
                                step="50"
                                class="w-full pl-7 pr-2 py-2.5 border border-gray-200 rounded-xl text-sm
                                       focus:ring-2 focus:ring-[#E48F62] outline-none"
                            >
                        </div>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 mb-1 block">Máximo</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                            <input
                                type="number"
                                wire:model.live.debounce.400ms="precioMax"
                                min="{{ $rangoAbsMin }}"
                                max="{{ $rangoAbsMax }}"
                                step="50"
                                class="w-full pl-7 pr-2 py-2.5 border border-gray-200 rounded-xl text-sm
                                       focus:ring-2 focus:ring-[#E48F62] outline-none"
                            >
                        </div>
                    </div>
                </div>
                <div class="flex justify-between text-xs text-gray-400 mt-1 px-1">
                    <span>${{ number_format($rangoAbsMin, 0) }}</span>
                    <span>${{ number_format($rangoAbsMax, 0) }}</span>
                </div>
            </div>

            {{-- Ordenar --}}
            <div>
                <label class="font-semibold text-gray-700 text-base">Ordenar por</label>
                <select
                    wire:model.live="ordenar"
                    class="mt-2 w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm
                           focus:ring-2 focus:ring-[#E48F62] outline-none bg-white">
                    <option value="destacados">Mejor calificados</option>
                    <option value="precio_asc">Precio: menor a mayor</option>
                    <option value="precio_desc">Precio: mayor a menor</option>
                    <option value="nombre">Nombre A–Z</option>
                </select>
            </div>

            {{-- Limpiar filtros --}}
            <button
                wire:click="limpiarFiltros"
                class="w-full text-center text-sm text-[#E48F62] hover:underline font-medium">
                × Limpiar todos los filtros
            </button>

        </aside>

        {{-- ═══════════════════════ GRID PRODUCTOS ═══════════════ --}}
        <div class="lg:col-span-3">

            {{-- Contador --}}
            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-gray-500">
                    <span class="font-semibold text-gray-700">{{ $productos->total() }}</span>
                    resultado{{ $productos->total() !== 1 ? 's' : '' }} encontrado{{ $productos->total() !== 1 ? 's' : '' }}
                </p>
            </div>

            @if($productos->total() === 0)
            <div class="text-center text-gray-400 py-24 flex flex-col items-center gap-4">
                <i class="fa-solid fa-box-open text-5xl text-gray-200"></i>
                <p class="text-lg font-medium">No se encontraron resultados.</p>
                <p class="text-sm">Intenta con otros filtros o términos de búsqueda.</p>
            </div>
            @endif

            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-8">
                @foreach ($productos as $p)
                    <x-product-card
                        :id="$p->Id"
                        :titulo="$p->nombre"
                        :precio="$p->precio"
                        :categoria="$p->categoria"
                        :estado="$p->estado_nombre"
                        :rating="$p->calificacion"
                        :descripcion="$p->descripcion"
                        :tipo="$p->tipo"
                    />
                @endforeach
            </div>

            {{-- PAGINADOR --}}
            @if($productos->hasPages())
            <div class="mt-12 flex justify-center gap-2 flex-wrap">
                @if($productos->onFirstPage())
                    <span class="px-4 py-2 rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed text-sm">←</span>
                @else
                    <button wire:click="previousPage"
                        class="px-4 py-2 rounded-xl bg-white border hover:bg-[#E48F62]
                               hover:text-white transition text-sm">←</button>
                @endif

                @foreach($productos->getUrlRange(1, $productos->lastPage()) as $page => $url)
                    @if($page == $productos->currentPage())
                        <span class="px-4 py-2 rounded-xl bg-[#E48F62] text-white font-bold text-sm">
                            {{ $page }}
                        </span>
                    @else
                        <button wire:click="gotoPage({{ $page }})"
                            class="px-4 py-2 rounded-xl bg-white border hover:bg-[#E48F62]
                                   hover:text-white transition text-sm">
                            {{ $page }}
                        </button>
                    @endif
                @endforeach

                @if($productos->hasMorePages())
                    <button wire:click="nextPage"
                        class="px-4 py-2 rounded-xl bg-white border hover:bg-[#E48F62]
                               hover:text-white transition text-sm">→</button>
                @else
                    <span class="px-4 py-2 rounded-xl bg-gray-100 text-gray-400 cursor-not-allowed text-sm">→</span>
                @endif
            </div>
            @endif

        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════════════════
     MODAL — Detalle + Especificaciones
     ════════════════════════════════════════════════════════ --}}
@if($productoDetalle && $detalleProducto)
<div
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    x-data="{ tab: 'info' }"
    wire:key="modal-{{ $productoDetalle }}">

    {{-- Backdrop --}}
    <div
        class="absolute inset-0 bg-black/60 backdrop-blur-sm"
        wire:click="cerrarDetalle">
    </div>

    {{-- Panel --}}
    <div class="relative z-10 bg-white rounded-3xl shadow-2xl w-full max-w-4xl
                max-h-[90vh] overflow-y-auto">

        {{-- Cerrar --}}
        <button
            wire:click="cerrarDetalle"
            class="absolute top-4 right-4 z-20 bg-gray-100 hover:bg-gray-200
                   text-gray-600 rounded-full w-9 h-9 flex items-center justify-center transition">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="grid md:grid-cols-2 gap-0">

            {{-- ── Carrusel (modal) ── --}}
            <div
                class="relative bg-gray-50 rounded-t-3xl md:rounded-l-3xl md:rounded-tr-none overflow-hidden"
                x-data="{ slide: 0, total: {{ $detalleImagenes->count() ?: 1 }} }">

                @if($detalleImagenes->isNotEmpty())
                    @foreach($detalleImagenes as $i => $img)
                    <img
                        src="{{ asset('images/' . $img->ruta) }}"
                        alt="{{ $img->alt_text ?? $detalleProducto->nombre }}"
                        x-show="slide === {{ $i }}"
                        class="w-full h-72 md:h-full object-cover"
                    >
                    @endforeach
                @else
                    <img
                        src="{{ asset('images/' . $detalleProducto->imagen) }}"
                        alt="{{ $detalleProducto->nombre }}"
                        class="w-full h-72 md:h-full object-cover"
                    >
                @endif

                {{-- Controles --}}
                @if($detalleImagenes->count() > 1)
                <button
                    x-on:click="slide = (slide - 1 + total) % total"
                    class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white
                           rounded-full w-9 h-9 flex items-center justify-center shadow transition">
                    <i class="fa-solid fa-chevron-left text-sm"></i>
                </button>
                <button
                    x-on:click="slide = (slide + 1) % total"
                    class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white
                           rounded-full w-9 h-9 flex items-center justify-center shadow transition">
                    <i class="fa-solid fa-chevron-right text-sm"></i>
                </button>

                {{-- Dots --}}
                <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5">
                    @foreach($detalleImagenes as $i => $img)
                    <button
                        x-on:click="slide = {{ $i }}"
                        :class="slide === {{ $i }} ? 'bg-[#E48F62] w-4' : 'bg-white/70 w-2'"
                        class="h-2 rounded-full transition-all duration-300">
                    </button>
                    @endforeach
                </div>
                @endif

                {{-- Badge tipo --}}
                {{-- Badges tipo + estado --}}
                <div class="absolute top-4 left-4 flex gap-2">

                    <span class="
                        {{ $detalleProducto->tipo === 'servicio'
                            ? 'bg-blue-500'
                            : 'bg-[#E48F62]' }}
                        text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                        {{ $detalleProducto->tipo }}
                    </span>

                    <span class="shrink-0 bg-green-100 text-green-700 font-semibold text-xs
                                 px-3 py-1 rounded-full">
                        {{ $detalleProducto->estado_nombre }}
                    </span>

                </div>
            </div>

            {{-- ── Info & Especificaciones ── --}}
            <div class="p-7 flex flex-col gap-4">

                <div class="flex items-start justify-between gap-2">
                    <div>
                        <p class="text-xs font-semibold text-[#E28987] uppercase tracking-wide">
                            {{ $detalleProducto->categoria }}
                        </p>
                        <h3 class="text-2xl font-extrabold text-gray-800 mt-1 leading-tight">
                            {{ $detalleProducto->nombre }}
                        </h3>
                    </div>

                </div>

                <x-rating-stars :rating="$detalleProducto->calificacion" />

                <p class="text-3xl font-extrabold text-[#E48F62]">
                    ${{ number_format($detalleProducto->precio, 2) }}
                </p>

                {{-- Tabs --}}
                <div class="border-b border-gray-100 flex gap-4 text-sm font-semibold">
                    <button
                        x-on:click="tab = 'info'"
                        :class="tab === 'info' ? 'border-b-2 border-[#E48F62] text-[#E48F62]' : 'text-gray-400'"
                        class="pb-2 transition">Descripción</button>
                    @if($detalleEspecifs->isNotEmpty())
                    <button
                        x-on:click="tab = 'specs'"
                        :class="tab === 'specs' ? 'border-b-2 border-[#E48F62] text-[#E48F62]' : 'text-gray-400'"
                        class="pb-2 transition">Especificaciones</button>
                    @endif
                </div>

                {{-- Tab: Descripción --}}
                <div x-show="tab === 'info'" class="text-gray-600 text-sm leading-relaxed">
                    {{ $detalleProducto->descripcion }}
                    @if($detalleProducto->tipo === 'producto')
                    <p class="mt-3 text-xs text-gray-400">
                        Disponibles: <span class="font-semibold text-gray-600">
                            {{ $detalleProducto->cantidad_disponible }}
                        </span> unidades
                    </p>
                    @endif
                </div>

                {{-- Tab: Especificaciones --}}
                @if($detalleEspecifs->isNotEmpty())
                <div x-show="tab === 'specs'" class="text-sm">
                    <table class="w-full border-collapse">
                        @foreach($detalleEspecifs as $esp)
                        <tr class="border-b border-gray-50 hover:bg-[#FFF8F5] transition">
                            <td class="py-2 pr-4 font-semibold text-gray-600 w-40">
                                {{ $esp->clave }}
                            </td>
                            <td class="py-2 text-gray-700">
                                {{ $esp->valor }}
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                @endif

                {{-- Acciones --}}
                <div class="flex gap-3 mt-auto pt-2">
                    @if($detalleProducto->tipo === 'producto')
                    <button class="flex-1 bg-[#E48F62] hover:bg-[#d07a4e] text-white font-bold
                                   py-3 rounded-2xl transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-cart-shopping"></i> Agregar al carrito
                    </button>
                    @else
                    <button class="flex-1 bg-[#E48F62] hover:bg-[#d07a4e] text-white font-bold
                                   py-3 rounded-2xl transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-calendar-check"></i> Contratar servicio
                    </button>
                    @endif
                    <button class="bg-white border-2 border-[#E28987] text-[#E28987]
                                   hover:bg-[#E28987] hover:text-white p-3 rounded-2xl transition">
                        <i class="fa-regular fa-heart"></i>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
@endif

</div>
