@props([
    'buscar' => '',
    'mostrarSugerencias' => false,
    'sugerencias' => collect(),
    'categoriasSeleccionadas' => [],
    'tipo' => '',
    'precioMin' => 0,
    'precioMax' => 0,
    'rangoAbsMin' => 0,
    'rangoAbsMax' => 0,
    'categorias' => collect(),
    'ordenar' => 'destacados', // solo buscar-productos lo usa
    'conOrdenar' => false, // mostrar bloque "Ordenar"
    'conEstrellas' => false, // mostrar bloque "Calificación"
    'calificacionMin' => 0, // solo tienda lo usa
    'productos' => collect()
])

<aside class="hidden lg:block w-72 shrink-0 lg:sticky lg:top-6 filter-card p-0 overflow-hidden bg-white self-start">

    {{-- Header --}}
    <div class="px-6 py-5 bg-gradient-to-r from-[#E48F62]/10 to-[#E28987]/5 border-b border-[#f0ede8]">
        <h3 class="tienda-display font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-sliders text-[#E48F62]"></i>
            Filtros
        </h3>
    </div>

    {{-- ── Buscar ── --}}
    <div class="filter-section px-6 py-5 relative">
        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">
            <i class="fa-solid fa-magnifying-glass mr-1 text-[#E48F62]"></i> Buscar
        </p>

        <p class="text-sm text-gray-500 mb-3">
            <span class="font-semibold text-gray-700">{{ $productos->total() }}</span>
            resultado{{ $productos->total() !== 1 ? 's' : '' }} encontrado{{ $productos->total() !== 1 ? 's' : '' }}
        </p>

        <div class="relative">
            <input type="text" wire:model.live.debounce.300ms="buscar" wire:focus="abrirSugerencias"
                wire:blur="cerrarSugerencias" placeholder="Nombre, descripción..."
                class="w-full bg-[#fafaf8] border border-[#e5e0d8] rounded-xl pl-4 pr-10 py-2.5
                       text-sm focus:ring-2 focus:ring-[#E48F62]/30 focus:border-[#E48F62] outline-none transition">
            @if ($buscar)
                <button wire:click="$set('buscar', '')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-300 hover:text-gray-500 transition">
                    <i wire:click="cerrarSugerencias" class="fa-solid fa-xmark text-xs"></i>
                </button>
            @endif
        </div>

        @if ($mostrarSugerencias && $sugerencias->isNotEmpty())
            <div
                class="absolute z-50 left-6 right-6 bg-white border border-[#e5e0d8]
                    mt-1 rounded-xl shadow-xl overflow-hidden">
                @foreach ($sugerencias as $s)
                    <button wire:click="seleccionarSugerencia({{ $s->Id }})"
                        class="w-full text-left px-4 py-3 hover:bg-[#FFF3EC]
                       border-b border-gray-50 last:border-0 transition flex gap-3">
                        <div class="mt-1">
                            <i class="fa-solid fa-magnifying-glass text-[#E48F62] text-xs"></i>
                        </div>
                        <div class="flex flex-col leading-tight">
                            <span class="text-sm font-semibold text-gray-800">{{ $s->nombre }}</span>
                            <span class="text-[11px] text-gray-400 uppercase tracking-wide">{{ $s->categoria }}</span>
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
            @foreach (['' => ['Todos', 'fa-border-all'], 'producto' => ['Producto', 'fa-box'], 'servicio' => ['Servicio', 'fa-hands-holding-circle']] as $val => [$label, $icon])
                <button wire:click="$set('tipo','{{ $val }}')"
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

    {{-- ── Categoría ── --}}
    <div class="filter-section px-6 py-5">
        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">
            <i class="fa-solid fa-folder-tree mr-1 text-[#E48F62]"></i> Categoría
        </p>
        <div class="space-y-2.5">
            @foreach ($categorias as $c)
                <label class="check-custom flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" wire:model.live="categoriasSeleccionadas" value="{{ $c->categoria }}">
                    <span class="flex items-center gap-2 text-sm text-gray-600 group-hover:text-[#E48F62] transition">
                        <i class="{{ $c->icono }} text-[#E48F62] w-4 text-center text-xs"></i>
                        {{ $c->categoria }}
                    </span>
                </label>
            @endforeach
        </div>
    </div>

    {{-- ── Precio ── --}}
    <div class="filter-section px-6 py-5">
        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">
            <i class="fa-solid fa-dollar-sign mr-1 text-[#E48F62]"></i> Precio
        </p>
        <div class="grid grid-cols-2 gap-3 mb-4">
            <div>
                <label class="text-[10px] text-gray-400 font-semibold uppercase tracking-wide block mb-1">Mín.</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-xs">$</span>
                    <input type="number" wire:model.live.debounce.400ms="precioMin" min="{{ $rangoAbsMin }}"
                        max="{{ $rangoAbsMax }}" step="50"
                        class="w-full pl-6 pr-2 py-2 bg-[#fafaf8] border border-[#e5e0d8] rounded-lg
                               text-sm focus:ring-2 focus:ring-[#E48F62]/30 focus:border-[#E48F62] outline-none">
                </div>
            </div>
            <div>
                <label class="text-[10px] text-gray-400 font-semibold uppercase tracking-wide block mb-1">Máx.</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-xs">$</span>
                    <input type="number" wire:model.live.debounce.400ms="precioMax" min="{{ $rangoAbsMin }}"
                        max="{{ $rangoAbsMax }}" step="50"
                        class="w-full pl-6 pr-2 py-2 bg-[#fafaf8] border border-[#e5e0d8] rounded-lg
                               text-sm focus:ring-2 focus:ring-[#E48F62]/30 focus:border-[#E48F62] outline-none">
                </div>
            </div>
        </div>
        <div class="flex justify-between text-xs text-gray-300 font-medium">
            <span>${{ number_format($rangoAbsMin, 0) }}</span>
            <span>${{ number_format($rangoAbsMax, 0) }}</span>
        </div>
    </div>

    {{-- ── Calificación (opcional) ── --}}
    @if ($conEstrellas)
        <div class="filter-section px-6 py-5">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">
                <i class="fa-solid fa-star mr-1 text-[#E48F62]"></i> Calificación mínima
            </p>
            <div class="space-y-2">
                @foreach ([5, 4, 3, 0] as $stars)
                    <label class="flex items-center gap-2 cursor-pointer group text-sm">
                        <input type="radio" wire:model.live="calificacionMin" value="{{ $stars }}"
                            class="accent-[#E48F62]">
                        @if ($stars === 0)
                            <span class="text-gray-500 group-hover:text-[#E48F62] transition text-xs">Todas</span>
                        @else
                            <span class="flex items-center gap-0.5">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i
                                        class="fa-solid fa-star text-xs {{ $i <= $stars ? 'text-[#ffbb51]' : 'text-gray-200' }}"></i>
                                @endfor
                                <span class="text-gray-400 text-xs ml-1">y más</span>
                            </span>
                        @endif
                    </label>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ── Ordenar (opcional) ── --}}
    @if ($conOrdenar)
        <div class="filter-section px-6 py-5">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">
                <i class="fa-solid fa-arrow-up-wide-short mr-1 text-[#E48F62]"></i> Ordenar
            </p>
            <select wire:model.live="ordenar"
                class="w-full text-sm border border-[#e5e0d8] rounded-lg px-3 py-2 bg-white
                   focus:ring-2 focus:ring-[#E48F62]/30 focus:border-[#E48F62] outline-none">
                <option value="destacados">Mejor calificados</option>
                <option value="precio_asc">Precio ↑</option>
                <option value="precio_desc">Precio ↓</option>
                <option value="nombre">Nombre A–Z</option>
            </select>
        </div>
    @endif

    {{-- Limpiar --}}
    <div class="px-6 py-4">
        <button wire:click="limpiarFiltros"
            class="w-full py-2.5 rounded-xl border border-[#e5e0d8] text-sm font-semibold
                   text-gray-500 hover:border-[#E48F62] hover:text-[#E48F62] transition flex items-center justify-center gap-2">
            <i class="fa-solid fa-filter-circle-xmark"></i>
            Limpiar filtros
        </button>
    </div>

</aside>
