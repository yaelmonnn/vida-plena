@props([
    'id',
    'titulo',
    'precio',
    'categoria',
    'estado',
    'rating',
    'descripcion',
    'tipo' => 'producto',
    'cantidadDisponible' => 0,
])

{{-- JS registrado en resources/js/product-card.js e importado en app.js --}}
<div x-data="productCard({{ $id }})" class="pcard-list group">

    {{-- ── Imagen ── --}}
    <div class="w-40 shrink-0 bg-[#f5f0ec] relative overflow-hidden">

        <template x-if="images.length > 0">
            <img :src="images[current].src" :alt="images[current].alt"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 min-h-[140px]">
        </template>
        <template x-if="images.length === 0 && loading">
            <div class="w-full min-h-[140px] bg-gradient-to-br from-[#f5f0ec] to-[#ede8e3] animate-pulse"></div>
        </template>


    </div>

    {{-- ── Contenido ── --}}
    <div class="flex-1 p-5 flex flex-col justify-between gap-2 min-w-0">
        <div>
            <div class="flex items-start justify-between gap-3 mb-1">

                <div>
                    <span class="text-[10px] font-bold text-[#E28987] uppercase tracking-widest">
                        {{ $categoria }}
                    </span>

                    <h3 class="tienda-display font-bold text-gray-800 text-base leading-snug mt-0.5">
                        {{ $titulo }}
                    </h3>
                </div>

                @php
                    $stockClass =
                        $cantidadDisponible > 10
                            ? 'badge-stock'
                            : ($cantidadDisponible > 0
                                ? 'badge-stock-low'
                                : 'badge-out');

                    $stockLabel =
                        $cantidadDisponible > 10
                            ? 'En stock'
                            : ($cantidadDisponible > 0
                                ? 'Pocas unidades'
                                : 'Agotado');
                @endphp

                {{-- grupo badges --}}
                <div class="flex items-center gap-1.5 shrink-0">

                    <span class="{{ $stockClass }} text-[10px] font-semibold px-2.5 py-1 rounded-full">
                        {{ $stockLabel }}
                    </span>

                    <span
                        class="
            {{ $tipo === 'servicio' ? 'bg-blue-500' : 'bg-[#E48F62]' }}
            text-white text-[10px] font-black px-2 py-0.5 rounded-full uppercase tracking-wide">
                        {{ $tipo }}
                    </span>

                </div>

            </div>

            {{-- Estrellas --}}
            <div class="flex items-center gap-1 mb-2">
                @for ($i = 1; $i <= 5; $i++)
                    <i class="fa-solid fa-star text-xs {{ $i <= $rating ? 'text-[#ffbb51]' : 'text-gray-200' }}"></i>
                @endfor
                <span class="text-xs text-gray-400 ml-1">{{ $rating }}.0</span>
            </div>

            <p class="text-gray-500 text-sm line-clamp-2">{{ $descripcion }}</p>
        </div>

        {{-- Precio + acciones --}}
        <div class="flex items-center justify-between pt-2 border-t border-[#f0ede8]">
            <span class="tienda-display font-black text-[#E48F62] text-xl">
                ${{ number_format($precio, 0) }}
            </span>

            <div class="flex gap-2">
                <button wire:click="abrirDetalle({{ $id }})"
                    class="px-3 py-2 rounded-xl border border-[#e5e0d8] hover:border-[#E48F62]
                           text-gray-400 hover:text-[#E48F62] text-sm transition flex items-center gap-1.5">
                    <i class="fa-solid fa-eye"></i>
                    <span class="text-xs font-semibold">Detalle</span>
                </button>

                <button class="btn-coral px-4 py-2 text-sm flex items-center gap-1.5">
                    <i class="fa-solid {{ $tipo === 'servicio' ? 'fa-calendar-check' : 'fa-cart-plus' }}"></i>
                    {{ $tipo === 'servicio' ? 'Contratar' : 'Añadir al carrito' }}
                </button>

                <button
                    class="w-9 h-9 rounded-xl border border-[#e5e0d8] hover:border-[#E28987]
                           text-gray-300 hover:text-[#E28987] flex items-center justify-center transition">
                    <i class="fa-regular fa-heart text-sm"></i>
                </button>
            </div>
        </div>
    </div>
</div>
