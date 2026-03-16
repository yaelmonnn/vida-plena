@props([
    'id',
    'titulo',
    'precio',
    'categoria',
    'estado',
    'rating',
    'descripcion',
    'tipo' => 'producto',
])

{{--
    La tarjeta carga sus propias imágenes directamente desde la DB.
    Alpine.js gestiona el mini-carrusel (sin JS externo).
--}}
<div
    x-data="productCard({{ $id }})"
    class="bg-white rounded-3xl shadow-md hover:shadow-2xl transition duration-300 group
           flex flex-col overflow-hidden border border-gray-50">

    {{-- ── CARRUSEL ── --}}
    <div class="relative overflow-hidden bg-gray-100 h-56">

        {{-- Imágenes --}}
        <template x-if="images.length > 0">
            <div class="relative w-full h-full">
                <template x-for="(img, i) in images" :key="i">
                    <img
                        :src="img.src"
                        :alt="img.alt"
                        x-show="current === i"
                        x-transition:enter="transition-opacity duration-400"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        class="absolute inset-0 w-full h-full object-cover
                               group-hover:scale-105 transition-transform duration-500"
                    >
                </template>
            </div>
        </template>

        {{-- Skeleton mientras carga --}}
        <template x-if="images.length === 0 && loading">
            <div class="w-full h-full bg-gray-200 animate-pulse"></div>
        </template>

        {{-- Controles (solo si hay más de 1 imagen) --}}
        <template x-if="images.length > 1">
            <div>
                <button
                    x-on:click.stop="prev()"
                    class="absolute left-2 top-1/2 -translate-y-1/2 z-10
                           bg-white/80 hover:bg-white rounded-full w-7 h-7
                           flex items-center justify-center shadow transition
                           opacity-0 group-hover:opacity-100">
                    <i class="fa-solid fa-chevron-left text-xs text-gray-600"></i>
                </button>
                <button
                    x-on:click.stop="next()"
                    class="absolute right-2 top-1/2 -translate-y-1/2 z-10
                           bg-white/80 hover:bg-white rounded-full w-7 h-7
                           flex items-center justify-center shadow transition
                           opacity-0 group-hover:opacity-100">
                    <i class="fa-solid fa-chevron-right text-xs text-gray-600"></i>
                </button>

                {{-- Dots --}}
                <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1 z-10">
                    <template x-for="(img, i) in images" :key="i">
                        <button
                            x-on:click.stop="current = i"
                            :class="current === i
                                ? 'bg-[#E48F62] w-3.5'
                                : 'bg-white/70 w-1.5'"
                            class="h-1.5 rounded-full transition-all duration-300">
                        </button>
                    </template>
                </div>
            </div>
        </template>

        {{-- Badge tipo --}}


    </div>

    {{-- ── CONTENIDO ── --}}
    <div class="p-5 flex flex-col gap-2 flex-1">

        <div class="flex justify-between items-center">
            <span class="text-xs font-semibold text-[#E28987] uppercase tracking-wide">
                {{ $categoria }}
            </span>
            <span class="bg-green-100 text-green-700 font-semibold text-[10px]
                         px-2.5 py-0.5 rounded-full">
                {{ $estado }}
            </span>
            <span
                class=" text-white text-[10px] font-bold
                    px-2.5 py-0.5 rounded-full uppercase tracking-wide z-10
                    {{ $tipo === 'servicio' ? 'bg-blue-500' : 'bg-[#E48F62]' }}">
                {{ $tipo }}
            </span>
        </div>

        <h3 class="text-base font-bold text-gray-800 line-clamp-2 leading-snug">
            {{ $titulo }}
        </h3>

        <p class="text-gray-500 text-sm line-clamp-2 flex-1">
            {{ $descripcion }}
        </p>

        <x-rating-stars :rating="$rating" />

        <div class="flex justify-between items-center pt-1 mt-auto">
            <span class="text-xl font-extrabold text-[#E48F62]">
                ${{ number_format($precio, 2) }}
            </span>

            <div class="flex items-center gap-2">
                {{-- Ver detalle (abre modal Livewire) --}}
                <button
                    wire:click="abrirDetalle({{ $id }})"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-600
                           p-2.5 rounded-xl transition text-sm"
                    title="Ver detalle">
                    <i class="fa-solid fa-eye"></i>
                </button>

                @if($tipo === 'producto')
                <button
                    class="bg-white border-2 border-[#E28987] text-[#E28987] p-2.5 rounded-xl
                           hover:bg-[#E28987] hover:text-white transition"
                    title="Agregar al carrito">
                    <i class="fa-solid fa-cart-shopping"></i>
                </button>
                @else
                <button
                    class="bg-white border-2 border-[#E28987] text-[#E28987] p-2.5 rounded-xl
                           hover:bg-[#E28987] hover:text-white transition"
                    title="Contratar">
                    <i class="fa-solid fa-calendar-check"></i>
                </button>
                @endif

                <button
                    class="bg-white border-2 border-[#ffbb51] text-[#ffbb51] p-2.5 rounded-xl
                           hover:bg-[#ffbb51] hover:text-white transition"
                    title="Favoritos">
                    <i class="fa-regular fa-heart"></i>
                </button>
            </div>
        </div>

    </div>
</div>

{{-- JS registrado en resources/js/product-card.js e importado en app.js --}}
