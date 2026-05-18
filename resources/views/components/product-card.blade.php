@props(['id', 'titulo', 'precio', 'categoria', 'estado', 'rating', 'descripcion', 'tipo' => 'producto'])

<div x-data="productCard({{ $id }})"
    class="bg-white rounded-3xl shadow-md hover:shadow-2xl transition-all duration-300 group
           flex flex-col overflow-hidden border border-gray-50 relative">

    {{-- ── CARRUSEL ── --}}
    <div class="relative overflow-hidden bg-white h-56">

        <template x-if="images.length > 0">
            <div class="relative w-full h-full">
                <template x-for="(img, i) in images" :key="i">
                    <img :src="img.src" :alt="img.alt" x-show="current === i"
                        x-transition:enter="transition-opacity duration-400"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        class="absolute inset-0 w-full h-full object-contain
                               group-hover:scale-105 transition-transform duration-500 cursor-pointer"
                        wire:click="abrirDetalle({{ $id }})">
                </template>
            </div>
        </template>

        <template x-if="images.length === 0 && loading">
            <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 animate-pulse"></div>
        </template>

        {{-- Controles carrusel --}}
        <template x-if="images.length > 1">
            <div>
                <button x-on:click.stop="prev()"
                    class="absolute left-2 top-1/2 -translate-y-1/2 z-10
                           bg-white/80 hover:bg-white rounded-full w-7 h-7
                           flex items-center justify-center shadow transition cursor-pointer
                           opacity-0 group-hover:opacity-100">
                    <i class="fa-solid fa-chevron-left text-xs text-gray-600"></i>
                </button>
                <button x-on:click.stop="next()"
                    class="absolute right-2 top-1/2 -translate-y-1/2 z-10
                           bg-white/80 hover:bg-white rounded-full w-7 h-7
                           flex items-center justify-center shadow transition cursor-pointer
                           opacity-0 group-hover:opacity-100">
                    <i class="fa-solid fa-chevron-right text-xs text-gray-600"></i>
                </button>
                <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1 z-10">
                    <template x-for="(img, i) in images" :key="i">
                        <button x-on:click.stop="current = i" cursor-pointer
                            :class="current === i ? 'bg-[#E48F62] w-3.5' : 'bg-white/70 w-1.5'"
                            class="h-1.5 rounded-full transition-all duration-300 cursor-pointer">
                        </button>
                    </template>
                </div>
            </div>
        </template>

        {{-- Badge tipo (esquina superior derecha) --}}
        <div class="absolute top-3 left-3 z-10">
            <span class="text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wide shadow
                {{ $tipo === 'servicio' ? 'bg-blue-500' : 'bg-[#E48F62]' }}">
                {{ $tipo }}
            </span>
        </div>
    </div>

    {{-- ── CONTENIDO ── --}}
    <div class="p-5 flex flex-col gap-2 flex-1">

        <div class="flex items-center justify-between gap-1">
            <span class="text-xs font-semibold text-[#E28987] uppercase tracking-wide truncate">
                {{ $categoria }}
            </span>
            <span class="bg-green-100 text-green-700 font-semibold text-[10px] px-2.5 py-0.5 rounded-full shrink-0">
                {{ $estado }}
            </span>
        </div>

        <h3 class="text-base font-bold text-gray-800 line-clamp-2 leading-snug cursor-pointer hover:text-[#E48F62] transition"
            wire:click="abrirDetalle({{ $id }})">
            {{ $titulo }}
        </h3>

        <p class="text-gray-500 text-sm line-clamp-2 flex-1">{{ $descripcion }}</p>

        <x-rating-stars :rating="$rating" />

        <div class="flex justify-between items-center pt-1 mt-auto">
            <span class="text-xl font-extrabold text-[#E48F62]">
                ${{ number_format($precio, 2) }}
            </span>

            <div class="flex items-center gap-2">

                {{-- Ver detalle --}}
                <button wire:click="abrirDetalle({{ $id }})"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-600 p-2.5 rounded-xl
                           transition text-sm cursor-pointer hover:scale-105 active:scale-95"
                    title="Ver detalle">
                    <i class="fa-solid fa-eye"></i>
                </button>

                {{-- Agregar al carrito / Contratar --}}
                @if ($tipo === 'producto')
                    <button
                        onclick="agregarAlCarrito({{ $id }}, this)"
                        class="bg-white border-2 border-[#E28987] text-[#E28987] p-2.5 rounded-xl
                               hover:bg-[#E28987] hover:text-white transition-all duration-200
                               cursor-pointer hover:scale-105 active:scale-95 hover:shadow-md"
                        title="Agregar al carrito">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </button>
                @else
                    <button
                        onclick="agregarAlCarrito({{ $id }}, this)"
                        class="bg-white border-2 border-blue-400 text-blue-400 p-2.5 rounded-xl
                               hover:bg-blue-400 hover:text-white transition-all duration-200
                               cursor-pointer hover:scale-105 active:scale-95 hover:shadow-md"
                        title="Contratar servicio">
                        <i class="fa-solid fa-calendar-check"></i>
                    </button>
                @endif

                {{-- Favoritos --}}
                <button
                    data-deseo-btn="{{ $id }}"
                    onclick="toggleDeseo({{ $id }}, this)"
                    class="deseo-inactivo bg-white border-2 border-[#ffbb51] text-[#ffbb51] p-2.5 rounded-xl
                           hover:bg-[#ffbb51] hover:text-white transition-all duration-200
                           cursor-pointer hover:scale-105 active:scale-95 hover:shadow-md"
                    title="Guardar en favoritos">
                    <i class="fa-regular fa-heart"></i>
                </button>

            </div>
        </div>

    </div>
</div>
