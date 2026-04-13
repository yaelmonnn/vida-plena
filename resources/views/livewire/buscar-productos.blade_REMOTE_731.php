


{{-- ════════════════════════════════════════════════════════
     VISTA  — Productos Destacados
     ════════════════════════════════════════════════════════ --}}
<div style="background: #fafaf8">
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

    <div class="flex gap-10 items-start">

        {{-- ═══════════════════════ SIDEBAR ══════════════════════ --}}
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
            :ordenar="$ordenar"
            :con-ordenar="true"
            :productos="$productos"
            {{-- con-estrellas no se pasa → queda false --}}
        />

        {{-- ═══════════════════════ GRID PRODUCTOS ═══════════════ --}}
        <div class="flex-1 min-w-0">



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

        </div>
    </div>
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
