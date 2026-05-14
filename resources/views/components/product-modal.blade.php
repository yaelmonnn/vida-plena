@props([
    'productoDetalle' => null,
    'detalleProducto' => null,
    'detalleImagenes' => collect(),
    'detalleEspecifs' => collect(),
    'detalleOpiniones' => collect(),
])

@if ($productoDetalle && $detalleProducto)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4"
     x-data="{ tab: 'info' }"
     wire:key="modal-{{ $productoDetalle }}">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm cursor-pointer"
         wire:click="cerrarDetalle"></div>

    {{-- Panel --}}
    <div class="relative z-10 bg-white rounded-3xl shadow-2xl w-full max-w-4xl
                max-h-[90vh] overflow-y-auto animate-modal-in">

        {{-- Cerrar --}}
        <button wire:click="cerrarDetalle"
            class="absolute top-4 right-4 z-20 bg-gray-100 hover:bg-red-100
                   text-gray-500 hover:text-red-500 rounded-full w-9 h-9
                   flex items-center justify-center transition-all cursor-pointer
                   hover:scale-110 active:scale-95">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="grid md:grid-cols-2 gap-0">

            {{-- ── Carrusel ── --}}
            <div class="relative bg-gray-50 rounded-t-3xl md:rounded-l-3xl md:rounded-tr-none overflow-hidden"
                 x-data="{ slide: 0, total: {{ $detalleImagenes->count() ?: 1 }} }">

                @if ($detalleImagenes->isNotEmpty())
                    @foreach ($detalleImagenes as $i => $img)
                        <img src="{{ asset('images/' . $img->ruta) }}"
                             alt="{{ $img->alt_text ?? $detalleProducto->nombre }}"
                             x-show="slide === {{ $i }}"
                             x-transition:enter="transition-opacity duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             class="w-full h-72 md:h-full object-contain">
                    @endforeach
                @else
                    <div class="w-full h-72 md:h-full bg-gray-100 flex items-center justify-center">
                        <i class="fa-solid fa-image text-4xl text-gray-300"></i>
                    </div>
                @endif

                @if ($detalleImagenes->count() > 1)
                    <button x-on:click="slide = (slide - 1 + total) % total"
                        class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white
                               rounded-full w-9 h-9 flex items-center justify-center shadow
                               transition cursor-pointer hover:scale-110 active:scale-95">
                        <i class="fa-solid fa-chevron-left text-sm"></i>
                    </button>
                    <button x-on:click="slide = (slide + 1) % total"
                        class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white
                               rounded-full w-9 h-9 flex items-center justify-center shadow
                               transition cursor-pointer hover:scale-110 active:scale-95">
                        <i class="fa-solid fa-chevron-right text-sm"></i>
                    </button>
                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5">
                        @foreach ($detalleImagenes as $i => $img)
                            <button x-on:click="slide = {{ $i }}" cursor-pointer
                                :class="slide === {{ $i }} ? 'bg-[#E48F62] w-4' : 'bg-white/70 w-2'"
                                class="h-2 rounded-full transition-all duration-300 cursor-pointer">
                            </button>
                        @endforeach
                    </div>
                @endif

                {{-- Badges --}}
                <div class="absolute top-4 left-4 flex gap-2">
                    <span class="{{ $detalleProducto->tipo === 'servicio' ? 'bg-blue-500' : 'bg-[#E48F62]' }}
                                 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide shadow">
                        {{ $detalleProducto->tipo }}
                    </span>
                    <span class="bg-green-100 text-green-700 font-semibold text-xs px-3 py-1 rounded-full shadow">
                        {{ $detalleProducto->estado_nombre }}
                    </span>
                </div>
            </div>

            {{-- ── Info ── --}}
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

                {{-- Disponibilidad rápida --}}
                @if ($detalleProducto->tipo === 'producto')
                    @php $disp = $detalleProducto->cantidad_disponible; @endphp
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ $disp > 0 ? 'bg-green-400' : 'bg-red-400' }} inline-block"></span>
                        <span class="text-xs text-gray-500">
                            {{ $disp > 10 ? 'En stock' : ($disp > 0 ? "Solo {$disp} disponibles" : 'Agotado') }}
                        </span>
                    </div>
                @endif

                {{-- Tabs --}}
                <div class="border-b border-gray-100 flex gap-4 text-sm font-semibold">
                    <button x-on:click="tab = 'info'"
                        :class="tab === 'info' ? 'border-b-2 border-[#E48F62] text-[#E48F62]' : 'text-gray-400 hover:text-gray-600'"
                        class="pb-2 transition cursor-pointer">Descripción</button>

                    @if ($detalleEspecifs->isNotEmpty())
                        <button x-on:click="tab = 'specs'"
                            :class="tab === 'specs' ? 'border-b-2 border-[#E48F62] text-[#E48F62]' : 'text-gray-400 hover:text-gray-600'"
                            class="pb-2 transition cursor-pointer">Especificaciones</button>
                    @endif

                    <button x-on:click="tab = 'opiniones'"
                        :class="tab === 'opiniones' ? 'border-b-2 border-[#E48F62] text-[#E48F62]' : 'text-gray-400 hover:text-gray-600'"
                        class="pb-2 transition cursor-pointer flex items-center gap-1.5">
                        Opiniones
                        @if ($detalleOpiniones->isNotEmpty())
                            <span class="bg-[#E48F62] text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none">
                                {{ $detalleOpiniones->count() }}
                            </span>
                        @endif
                    </button>
                </div>

                {{-- Tab: Descripción --}}
                <div x-show="tab === 'info'" class="text-gray-600 text-sm leading-relaxed">
                    {{ $detalleProducto->descripcion }}
                </div>

                {{-- Tab: Especificaciones --}}
                @if ($detalleEspecifs->isNotEmpty())
                    <div x-show="tab === 'specs'" class="text-sm">
                        <table class="w-full border-collapse">
                            @foreach ($detalleEspecifs as $esp)
                                <tr class="border-b border-gray-50 hover:bg-[#FFF8F5] transition">
                                    <td class="py-2 pr-4 font-semibold text-gray-600 w-40">{{ $esp->clave }}</td>
                                    <td class="py-2 text-gray-700">{{ $esp->valor }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @endif

                {{-- Tab: Opiniones --}}
                <div x-show="tab === 'opiniones'" class="text-sm space-y-3 max-h-52 overflow-y-auto pr-1">
                    @if ($detalleOpiniones->isEmpty())
                        <div class="flex flex-col items-center py-8 text-gray-300 gap-2">
                            <i class="fa-regular fa-comments text-3xl"></i>
                            <p class="text-xs">Aún no hay opiniones para este producto.</p>
                        </div>
                    @else
                        @foreach ($detalleOpiniones as $op)
                            <div class="bg-[#fafaf8] rounded-xl p-3 border border-[#f0ede8]">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-bold text-gray-700 text-xs">{{ $op->autor }}</span>
                                    <div class="flex gap-0.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fa-solid fa-star text-[10px]
                                               {{ $i <= $op->calificacion ? 'text-[#ffbb51]' : 'text-gray-200' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-gray-500 leading-relaxed">{{ $op->comentario }}</p>
                                <p class="text-[10px] text-gray-300 mt-1.5">
                                    {{ \Carbon\Carbon::parse($op->fr)->diffForHumans() }}
                                </p>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- ── Acciones ── --}}
                <div class="flex gap-3 mt-auto pt-2">
                    @if ($detalleProducto->tipo === 'producto')
                        <button onclick="agregarAlCarrito({{ $detalleProducto->Id }}, this)"
                            class="flex-1 bg-white border-2 border-[#E48F62] text-[#E48F62] font-bold
                                   py-3 rounded-2xl transition-all duration-200 flex items-center justify-center gap-2
                                   hover:bg-[#E48F62] hover:text-white cursor-pointer
                                   hover:scale-[1.02] active:scale-[0.98] hover:shadow-md">
                            <i class="fa-solid fa-cart-shopping"></i> Añadir al carrito
                        </button>
                    @else
                        <button onclick="agregarAlCarrito({{ $detalleProducto->Id }}, this)"
                            class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold
                                   py-3 rounded-2xl transition-all duration-200 flex items-center justify-center gap-2
                                   cursor-pointer hover:scale-[1.02] active:scale-[0.98] hover:shadow-md">
                            <i class="fa-solid fa-calendar-check"></i> Contratar servicio
                        </button>
                    @endif

                    {{-- Favoritos --}}
                    <button
                        data-deseo-btn="{{ $detalleProducto->Id }}"
                        onclick="toggleDeseo({{ $detalleProducto->Id }}, this)"
                        class="deseo-inactivo bg-white border-2 border-[#E28987] text-[#E28987]
                               hover:bg-[#E28987] hover:text-white p-3 rounded-2xl
                               transition-all duration-200 cursor-pointer
                               hover:scale-110 active:scale-95"
                        title="Guardar en favoritos">
                        <i class="fa-regular fa-heart"></i>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
@endif

<style>
@keyframes modalIn {
    from { opacity: 0; transform: scale(0.95) translateY(10px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-modal-in { animation: modalIn 0.25s ease; }
</style>
