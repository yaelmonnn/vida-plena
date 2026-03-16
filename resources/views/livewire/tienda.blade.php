{{-- resources/views/livewire/tienda.blade.php --}}
<div>
<section class="max-w-7xl mx-auto px-4 md:px-6 py-10">

    {{-- ══════════ ACTIVE FILTER PILLS ══════════ --}}
    @php
        $hayFiltros = $buscar || !empty($categoriasSeleccionadas) || $tipo;
    @endphp
    @if($hayFiltros)
    <div class="flex flex-wrap gap-2 mb-6 items-center">
        <span class="text-xs text-gray-400 font-semibold uppercase tracking-wide mr-1">Filtros activos:</span>

        @if($buscar)
        <span class="active-filter-pill">
            <i class="fa-solid fa-magnifying-glass text-[10px]"></i>
            "{{ $buscar }}"
            <button wire:click="$set('buscar', '')" class="ml-1 hover:text-red-400">×</button>
        </span>
        @endif

        @foreach($categoriasSeleccionadas as $cat)
        <span class="active-filter-pill">
            <i class="fa-solid fa-tag text-[10px]"></i>
            {{ $cat }}
            <button wire:click="$set('categoriasSeleccionadas', array_values(array_diff($categoriasSeleccionadas, ['{{ $cat }}'])))" class="ml-1 hover:text-red-400">×</button>
        </span>
        @endforeach

        @if($tipo)
        <span class="active-filter-pill">
            <i class="fa-solid fa-layer-group text-[10px]"></i>
            {{ ucfirst($tipo) }}
            <button wire:click="$set('tipo', '')" class="ml-1 hover:text-red-400">×</button>
        </span>
        @endif

        <button wire:click="limpiarFiltros" class="text-xs text-gray-400 hover:text-[#E48F62] underline underline-offset-2 ml-2 transition">
            Limpiar todo
        </button>
    </div>
    @endif

    <div class="flex gap-8 items-start">

        {{-- ══════════════════ SIDEBAR ══════════════════ --}}
        <aside class="hidden lg:block w-72 shrink-0 lg:sticky lg:top-6 filter-card p-0 overflow-hidden">

            {{-- Header sidebar --}}
            <div class="px-6 py-5 bg-gradient-to-r from-[#E48F62]/10 to-[#E28987]/5 border-b border-[#f0ede8]">
                <h3 class="tienda-display font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-[#E48F62]"></i>
                    Filtros
                </h3>
            </div>

            {{-- ── Buscador ── --}}
            <div class="filter-section px-6 py-5 relative">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">
                    <i class="fa-solid fa-magnifying-glass mr-1 text-[#E48F62]"></i> Buscar
                </p>
                <div class="relative">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="buscar"
                        wire:focus="abrirSugerencias"
                        wire:blur="cerrarSugerencias"
                        placeholder="Producto, descripción..."
                        class="w-full bg-[#fafaf8] border border-[#e5e0d8] rounded-xl pl-4 pr-10 py-2.5
                               text-sm focus:ring-2 focus:ring-[#E48F62]/30 focus:border-[#E48F62] outline-none transition"
                    >
                    @if($buscar)
                    <button wire:click="$set('buscar', '')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-300 hover:text-gray-500 transition">
                        <i wire:click="cerrarSugerencias" class="fa-solid fa-xmark text-xs"></i>
                    </button>
                    @endif
                </div>
                    @if($mostrarSugerencias && $sugerencias->isNotEmpty())
                    <div class="absolute z-50 left-6 right-6 bg-white border border-[#e5e0d8]
                                mt-1 rounded-xl shadow-xl overflow-hidden">

                        @foreach($sugerencias as $s)
                        <button
                            wire:click="seleccionarSugerencia({{ $s->Id }})"
                            class="w-full text-left px-4 py-3 hover:bg-[#FFF3EC]
                                border-b border-gray-50 last:border-0 transition flex gap-3">

                            {{-- Icono --}}
                            <div class="mt-1">
                                <i class="fa-solid fa-magnifying-glass text-[#E48F62] text-xs"></i>
                            </div>

                            {{-- Contenido --}}
                            <div class="flex flex-col leading-tight">

                                {{-- Nombre (principal) --}}
                                <span class="text-sm font-semibold text-gray-800">
                                    {{ $s->nombre }}
                                </span>

                                {{-- Categoría (subtexto) --}}
                                <span class="text-[11px] text-gray-400 uppercase tracking-wide">
                                    {{ $s->categoria }}
                                </span>

                            </div>

                        </button>
                        @endforeach

                    </div>
                    @endif
            </div>

            {{-- ── Tipo ── --}}
            <div class="filter-section px-6 py-5">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">
                    <i class="fa-solid fa-layer-group mr-1 text-[#E48F62]"></i> Tipo
                </p>
                <div class="grid grid-cols-3 gap-2">
                    @foreach(['' => ['Todos', 'fa-border-all'], 'producto' => ['Producto', 'fa-box'], 'servicio' => ['Servicio', 'fa-hands-holding-circle']] as $val => [$label, $icon])
                    <button
                        wire:click="$set('tipo', '{{ $val }}')"
                        class="flex flex-col items-center gap-1 py-2.5 px-1 rounded-xl text-xs font-semibold
                               border transition cursor-pointer
                               {{ $tipo === $val
                                   ? 'bg-[#E48F62] text-white border-[#E48F62] shadow-md'
                                   : 'bg-[#fafaf8] text-gray-500 border-[#e5e0d8] hover:border-[#E48F62] hover:text-[#E48F62]' }}">
                        <i class="fa-solid {{ $icon }} text-sm"></i>
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- ── Categorías ── --}}
            <div class="filter-section px-6 py-5">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">
                    <i class="fa-solid fa-folder-tree mr-1 text-[#E48F62]"></i> Categoría
                </p>
                <div class="space-y-2.5">
                    @foreach ($categorias as $c)
                    <label class="check-custom flex items-center gap-3 cursor-pointer group">
                        <input
                            type="checkbox"
                            wire:model.live="categoriasSeleccionadas"
                            value="{{ $c->categoria }}"
                        >
                        <span class="flex items-center gap-2 text-sm text-gray-600 group-hover:text-[#E48F62] transition">
                            <i class="{{ $c->icono }} text-[#E48F62] w-4 text-center text-xs"></i>
                            {{ $c->categoria }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- ── Rango de precio ── --}}
            <div class="filter-section px-6 py-5">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">
                    <i class="fa-solid fa-dollar-sign mr-1 text-[#E48F62]"></i> Precio
                </p>
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <label class="text-[10px] text-gray-400 font-semibold uppercase tracking-wide block mb-1">Mín.</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-xs">$</span>
                            <input
                                type="number"
                                wire:model.live.debounce.400ms="precioMin"
                                min="{{ $rangoAbsMin }}" max="{{ $rangoAbsMax }}" step="50"
                                class="w-full pl-6 pr-2 py-2 bg-[#fafaf8] border border-[#e5e0d8] rounded-lg
                                       text-sm focus:ring-2 focus:ring-[#E48F62]/30 focus:border-[#E48F62] outline-none"
                            >
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] text-gray-400 font-semibold uppercase tracking-wide block mb-1">Máx.</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-xs">$</span>
                            <input
                                type="number"
                                wire:model.live.debounce.400ms="precioMax"
                                min="{{ $rangoAbsMin }}" max="{{ $rangoAbsMax }}" step="50"
                                class="w-full pl-6 pr-2 py-2 bg-[#fafaf8] border border-[#e5e0d8] rounded-lg
                                       text-sm focus:ring-2 focus:ring-[#E48F62]/30 focus:border-[#E48F62] outline-none"
                            >
                        </div>
                    </div>
                </div>
                <div class="flex justify-between text-xs text-gray-300 font-medium">
                    <span>${{ number_format($rangoAbsMin, 0) }}</span>
                    <span>${{ number_format($rangoAbsMax, 0) }}</span>
                </div>
            </div>

            {{-- ── Calificación ── --}}
            <div class="filter-section px-6 py-5">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">
                    <i class="fa-solid fa-star mr-1 text-[#E48F62]"></i> Calificación mínima
                </p>
                <div class="space-y-2">
                    @foreach([5,4,3,0] as $stars)
                    <label class="flex items-center gap-2 cursor-pointer group text-sm">
                        <input type="radio" wire:model.live="calificacionMin" value="{{ $stars }}"
                               class="accent-[#E48F62]">
                        @if($stars === 0)
                            <span class="text-gray-500 group-hover:text-[#E48F62] transition text-xs">Todas</span>
                        @else
                            <span class="flex items-center gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star text-xs {{ $i <= $stars ? 'text-[#ffbb51]' : 'text-gray-200' }}"></i>
                                @endfor
                                <span class="text-gray-400 text-xs ml-1">y más</span>
                            </span>
                        @endif
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Limpiar --}}
            <div class="px-6 py-4">
                <button
                    wire:click="limpiarFiltros"
                    class="w-full py-2.5 rounded-xl border border-[#e5e0d8] text-sm font-semibold
                           text-gray-500 hover:border-[#E48F62] hover:text-[#E48F62] transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-filter-circle-xmark"></i>
                    Limpiar filtros
                </button>
            </div>

        </aside>

        {{-- ══════════════════ CONTENIDO PRINCIPAL ══════════════════ --}}
        <div class="flex-1 min-w-0">

            {{-- ── Toolbar ── --}}
            <div class="toolbar px-4 py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">

                <p class="text-sm text-gray-500">
                    <span class="tienda-display font-bold text-gray-800 text-lg">{{ $productos->total() }}</span>
                    <span class="ml-1">resultado{{ $productos->total() !== 1 ? 's' : '' }}</span>
                </p>

                <div class="flex items-center gap-3 flex-wrap">

                    {{-- Ordenar --}}
                    <div class="flex items-center gap-2">
                        <label class="text-xs text-gray-400 font-semibold uppercase tracking-wide whitespace-nowrap">
                            <i class="fa-solid fa-arrow-up-wide-short mr-1"></i>Ordenar
                        </label>
                        <select wire:model.live="ordenar"
                            class="text-sm border border-[#e5e0d8] rounded-lg px-3 py-2 bg-white
                                   focus:ring-2 focus:ring-[#E48F62]/30 focus:border-[#E48F62] outline-none">
                            <option value="nombre">Nombre A–Z</option>
                            <option value="nombre_desc">Nombre Z–A</option>
                            <option value="precio_asc">Precio ↑</option>
                            <option value="precio_desc">Precio ↓</option>
                            <option value="calificacion">Mejor valorados</option>
                        </select>
                    </div>

                    {{-- Vista grid/lista --}}
                    <div class="flex items-center gap-1 bg-[#fafaf8] rounded-lg p-1 border border-[#e5e0d8]">
                        <button wire:click="$set('vista', 'grid')"
                            class="p-1.5 rounded-md transition {{ $vista === 'grid' ? 'bg-white shadow text-[#E48F62]' : 'text-gray-400 hover:text-gray-600' }}">
                            <i class="fa-solid fa-grip text-sm"></i>
                        </button>
                        <button wire:click="$set('vista', 'lista')"
                            class="p-1.5 rounded-md transition {{ $vista === 'lista' ? 'bg-white shadow text-[#E48F62]' : 'text-gray-400 hover:text-gray-600' }}">
                            <i class="fa-solid fa-list text-sm"></i>
                        </button>
                    </div>

                    {{-- Filtros móvil (botón) --}}
                    <button
                        x-data
                        x-on:click="$dispatch('abrir-filtros-movil')"
                        class="lg:hidden flex items-center gap-2 btn-coral px-4 py-2 text-sm">
                        <i class="fa-solid fa-sliders"></i> Filtros
                    </button>

                </div>
            </div>

            {{-- ── Sin resultados ── --}}
            @if($productos->total() === 0)
            <div class="flex flex-col items-center justify-center py-32 text-center">
                <div class="w-20 h-20 rounded-full bg-[#FFF3EC] flex items-center justify-center mb-5">
                    <i class="fa-solid fa-box-open text-3xl text-[#E48F62]/50"></i>
                </div>
                <h3 class="tienda-display font-bold text-gray-700 text-xl mb-2">Sin resultados</h3>
                <p class="text-gray-400 text-sm max-w-xs">Intenta ajustar los filtros o buscar con otros términos.</p>
                <button wire:click="limpiarFiltros"
                    class="mt-6 btn-coral px-6 py-2.5 text-sm">
                    <i class="fa-solid fa-rotate-left mr-1"></i> Resetear filtros
                </button>
            </div>
            @endif

            {{-- ── GRID ── --}}
            @if($vista === 'grid')
            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-8">
                @foreach($productos as $p)
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
            @endif

            {{-- ── LISTA ── --}}
            @if($vista === 'lista')
            <div class="flex flex-col gap-4">
                @foreach($productos as $p)
                    <x-product-list-item
                        :id="$p->Id"
                        :titulo="$p->nombre"
                        :precio="$p->precio"
                        :categoria="$p->categoria"
                        :estado="$p->estado_nombre"
                        :rating="$p->calificacion"
                        :descripcion="$p->descripcion"
                        :tipo="$p->tipo"
                        :cantidad-disponible="$p->cantidad_disponible"
                    />
                @endforeach
            </div>
            @endif


            {{-- ── PAGINADOR ── --}}
            @if($productos->hasPages())
            <div class="mt-12 flex justify-center items-center gap-2 flex-wrap">

                <button wire:click="previousPage"
                    class="page-btn {{ $productos->onFirstPage() ? 'disabled' : '' }}">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </button>

                @foreach($productos->getUrlRange(1, $productos->lastPage()) as $page => $url)
                    @if($page == $productos->currentPage())
                        <span class="page-btn active">{{ $page }}</span>
                    @elseif(abs($page - $productos->currentPage()) <= 2 || $page == 1 || $page == $productos->lastPage())
                        <button wire:click="gotoPage({{ $page }})" class="page-btn">{{ $page }}</button>
                    @elseif(abs($page - $productos->currentPage()) == 3)
                        <span class="page-btn disabled" style="border:none;background:transparent">…</span>
                    @endif
                @endforeach

                <button wire:click="nextPage"
                    class="page-btn {{ !$productos->hasMorePages() ? 'disabled' : '' }}">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>

            </div>
            @endif

        </div>{{-- end contenido --}}
    </div>{{-- end flex --}}

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
                    <div class="w-full h-72 md:h-full bg-gray-100 flex items-center justify-center">
                        <i class="fa-solid fa-image text-4xl text-gray-300"></i>
                    </div>
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

                {{-- Badges tipo + estado --}}
                <div class="absolute top-4 left-4 flex gap-2">
                    <span class="{{ $detalleProducto->tipo === 'servicio' ? 'bg-blue-500' : 'bg-[#E48F62]' }}
                        text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                        {{ $detalleProducto->tipo }}
                    </span>
                    <span class="shrink-0 bg-green-100 text-green-700 font-semibold text-xs px-3 py-1 rounded-full">
                        {{ $detalleProducto->estado_nombre }}
                    </span>
                </div>
            </div>

            {{-- ── Info & Especificaciones ── --}}
            <div class="p-7 flex flex-col gap-4">

                <div>
                    <p class="text-xs font-semibold text-[#E28987] uppercase tracking-wide">
                        {{ $detalleProducto->categoria }}
                    </p>
                    <h3 class="text-2xl font-extrabold text-gray-800 mt-1 leading-tight">
                        {{ $detalleProducto->nombre }}
                    </h3>
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
