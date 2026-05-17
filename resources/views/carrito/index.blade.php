@extends('layout.app')

@section('content')
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;900&family=Lato:ital,wght@0,300;0,400;0,700;1,300&display=swap"
        rel="stylesheet">

    <div class="min-h-screen bg-[#fafaf8] pt-28 pb-16 px-4">
        <div class="max-w-5xl mx-auto">

            {{-- Encabezado --}}
            <div class="mb-10">
                <h1 class="text-4xl font-extrabold text-gray-800">
                    Mi <span class="text-[#E48F62]">Carrito</span>
                </h1>
                <p class="text-gray-400 text-sm mt-1">
                    {{ $items->count() }} {{ $items->count() === 1 ? 'producto' : 'productos' }} en tu carrito
                </p>
            </div>

            @if (session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: '¡Listo!',
                        text: '{{ session('success') }}',
                        confirmButtonColor: '#E48F62',
                        timer: 2000,
                        showConfirmButton: false
                    });
                </script>
            @endif

            @if ($items->isEmpty())
                {{-- Carrito vacío --}}
                <div class="flex flex-col items-center justify-center py-32 text-center">
                    <div class="w-24 h-24 rounded-full bg-[#FFF3EC] flex items-center justify-center mb-6">
                        <i class="fa-solid fa-cart-shopping text-4xl text-[#E48F62]/40"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-700 mb-2">Tu carrito está vacío</h2>
                    <p class="text-gray-400 text-sm max-w-xs mb-8">
                        Explora nuestros productos y servicios y agrega lo que más te guste.
                    </p>
                    <a href="{{ route('tienda') }}"
                        class="bg-[#E48F62] hover:bg-[#d07a4e] text-white font-bold px-8 py-3
                  rounded-2xl transition flex items-center gap-2">
                        <i class="fa-solid fa-store"></i> Ir a la tienda
                    </a>
                </div>
            @else
                <div class="flex flex-col lg:flex-row gap-8 items-start">

                    {{-- ── Lista de productos ── --}}
                    <div class="flex-1 min-w-0 space-y-4">

                        @foreach ($items as $item)
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100
                        flex gap-4 p-4 hover:shadow-md transition group"
                                id="item-{{ $item->Id }}">

                                {{-- Imagen --}}
                                <div
                                    class="w-24 h-24 rounded-xl bg-gray-50 border border-gray-100
                            flex items-center justify-center shrink-0 overflow-hidden">
                                    @php
                                        $img = $item->producto->imagenes->first();
                                    @endphp
                                    @if ($img)
                                        <img src="{{ asset('images/' . $img->ruta) }}" alt="{{ $item->producto->nombre }}"
                                            class="w-full h-full object-contain">
                                    @else
                                        <i class="fa-solid fa-image text-2xl text-gray-200"></i>
                                    @endif
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <span
                                                class="text-[10px] font-bold uppercase tracking-wide
                                         {{ $item->producto->tipo === 'servicio' ? 'text-blue-500' : 'text-[#E48F62]' }}">
                                                {{ $item->producto->tipo }}
                                            </span>
                                            <h3 class="font-bold text-gray-800 text-sm leading-snug mt-0.5 line-clamp-2">
                                                {{ $item->producto->nombre }}
                                            </h3>
                                        </div>
                                        {{-- Eliminar --}}
                                        <form method="POST" action="{{ route('carrito.eliminar', $item->Id) }}"
                                            id="form-eliminar-{{ $item->Id }}">
                                            @csrf @method('DELETE')
                                            <button type="button" onclick="confirmarEliminar({{ $item->Id }})"
                                                class="text-gray-300 hover:text-red-400 transition p-1.5 rounded-lg
                   hover:bg-red-50 shrink-0">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <p class="text-[#E48F62] font-extrabold text-lg mt-1">
                                        ${{ number_format($item->producto->precio, 2) }}
                                    </p>

                                    {{-- Cantidad --}}
                                    <div class="flex items-center gap-3 mt-2">
                                        <span class="text-xs text-gray-400 font-semibold">Cantidad:</span>
                                        <div class="flex items-center gap-1">
                                            <button onclick="cambiarCantidad({{ $item->Id }}, -1)"
                                                class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600
                                       flex items-center justify-center transition text-xs font-bold">
                                                −
                                            </button>
                                            <span id="qty-{{ $item->Id }}"
                                                class="w-8 text-center font-bold text-gray-800 text-sm">
                                                {{ $item->cantidad }}
                                            </span>
                                            <button onclick="cambiarCantidad({{ $item->Id }}, 1)"
                                                class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600
                                       flex items-center justify-center transition text-xs font-bold">
                                                +
                                            </button>
                                        </div>
                                        <span class="text-xs text-gray-300 ml-auto">
                                            Subtotal:
                                            <span id="sub-{{ $item->Id }}" class="font-bold text-gray-600">
                                                ${{ number_format($item->producto->precio * $item->cantidad, 2) }}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- Limpiar carrito (opcional) --}}
                        <div class="text-right pt-2">
                            <a href="{{ route('tienda') }}" class="text-sm text-[#E48F62] hover:underline font-semibold">
                                <i class="fa-solid fa-arrow-left mr-1 text-xs"></i> Seguir comprando
                            </a>
                        </div>
                    </div>

                    {{-- ── Resumen / Total ── --}}
                    <div class="w-full lg:w-80 shrink-0">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-28">

                            <h2 class="text-lg font-extrabold text-gray-800 mb-5">Resumen del pedido</h2>

                            <div class="space-y-3 text-sm text-gray-500 border-b border-gray-100 pb-4 mb-4">
                                <div class="flex justify-between">
                                    <span>Subtotal ({{ $items->sum('cantidad') }} artículos)</span>
                                    <span class="font-semibold text-gray-700" id="resumen-subtotal">
                                        ${{ number_format($total, 2) }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Envío</span>
                                    <span class="text-green-600 font-semibold">Gratis</span>
                                </div>
                            </div>

                            <div class="flex justify-between items-center mb-6">
                                <span class="font-extrabold text-gray-800 text-base">Total</span>
                                <span class="text-2xl font-extrabold text-[#E48F62]" id="resumen-total">
                                    ${{ number_format($total, 2) }}
                                </span>
                            </div>

                            <a href="{{ route('checkout.index') }}"
                                class="w-full bg-[#E48F62] text-white font-bold py-3.5 rounded-2xl
                           flex items-center justify-center gap-2 hover:bg-[#d17c50] text-sm">
                                <i class="fa-solid fa-lock text-xs"></i>
                                Pagar
                            </a>

                            <p class="text-center text-[10px] text-gray-300 mt-3 flex items-center justify-center gap-1">
                                <i class="fa-solid fa-shield-halved"></i>
                                Compra 100% segura · Datos protegidos
                            </p>
                        </div>
                    </div>

                </div>
            @endif

        </div>
    </div>

    @push('scripts')


        <script>
            function confirmarEliminar(itemId) {
                Swal.fire({
                    title: '¿Eliminar producto?',
                    text: 'Se quitará este artículo de tu carrito.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    reverseButtons: true,
                }).then(result => {
                    if (result.isConfirmed) {
                        document.getElementById('form-eliminar-' + itemId).submit();
                    }
                });
            }


            const precios = {
                @foreach ($items as $item)
                    {{ $item->Id }}: {{ $item->producto->precio }},
                @endforeach
            };

            const cantidades = {
                @foreach ($items as $item)
                    {{ $item->Id }}: {{ $item->cantidad }},
                @endforeach
            };

            function cambiarCantidad(itemId, delta) {
                const nueva = Math.max(1, cantidades[itemId] + delta);
                cantidades[itemId] = nueva;

                document.getElementById('qty-' + itemId).textContent = nueva;
                document.getElementById('sub-' + itemId).textContent =
                    '$' + (precios[itemId] * nueva).toLocaleString('es-MX', {
                        minimumFractionDigits: 2
                    });

                // Recalcular total
                const total = Object.entries(cantidades).reduce((acc, [id, qty]) => acc + (precios[id] * qty), 0);
                document.getElementById('resumen-subtotal').textContent =
                    document.getElementById('resumen-total').textContent =
                    '$' + total.toLocaleString('es-MX', {
                        minimumFractionDigits: 2
                    });

                // Persistir en BD
                fetch(`/carrito/${itemId}/cantidad`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        cantidad: nueva
                    }),
                });
            }
        </script>
    @endpush

@endsection
