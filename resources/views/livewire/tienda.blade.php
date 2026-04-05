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
        <x-filter-sidebar
            :buscar="$buscar"
            :mostrar-sugerencias="$mostrarSugerencias"
            :sugerencias="$sugerencias"
            :categorias-seleccionadas="$categoriasSeleccionadas"
            :tipo="$tipo"
            :precio-min="$precioMin"
            :precio-max="$precioMax"
            :rango-abs-min="$rangoAbsMin"
            :rango-abs-max="$rangoAbsMax"
            :categorias="$categorias"
            :con-estrellas="true"
            :calificacion-min="$calificacionMin"
            :productos="$productos"
            {{-- con-ordenar no se pasa → queda false, tienda usa su toolbar propio --}}
        />

        {{-- ══════════════════ CONTENIDO PRINCIPAL ══════════════════ --}}
        <div class="flex-1 min-w-0">

            {{-- ── Toolbar ── --}}
            <div class="toolbar px-4 py-3 flex sm:flex-row sm:items-center justify-end gap-3 mb-6">

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

                {{-- Filtros móvil --}}
                <button
                    x-data
                    x-on:click="$dispatch('abrir-filtros-movil')"
                    class="lg:hidden flex items-center gap-2 btn-coral px-4 py-2 text-sm">
                    <i class="fa-solid fa-sliders"></i> Filtros
                </button>

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
<x-product-modal
    :producto-detalle="$productoDetalle"
    :detalle-producto="$detalleProducto"
    :detalle-imagenes="$detalleImagenes"
    :detalle-especifs="$detalleEspecifs"
    :detalle-opiniones="$detalleOpiniones"
/>

</div>
