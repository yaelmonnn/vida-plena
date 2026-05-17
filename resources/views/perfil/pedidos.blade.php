{{-- resources/views/perfil/pedidos.blade.php --}}
@extends('layout.app')

@section('content')
<div class="min-h-screen bg-[#fafaf8] pt-28 pb-16 px-4">
<div class="max-w-5xl mx-auto">

    {{-- ── Encabezado ── --}}
    <div class="mb-10 flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-4xl font-extrabold text-gray-800">
                Mis <span class="text-[#E48F62]">Pedidos</span>
                <i class="fa-solid fa-box text-[#E48F62] text-3xl ml-2 align-middle"></i>
            </h1>
            <p class="text-gray-400 text-sm mt-1">
                {{ $pedidos->total() }} {{ $pedidos->total() === 1 ? 'pedido realizado' : 'pedidos realizados' }}
            </p>
        </div>
        <a href="{{ route('tienda') }}"
           class="flex items-center gap-2 text-sm text-[#E48F62] hover:underline font-semibold">
            <i class="fa-solid fa-arrow-left text-xs"></i> Seguir comprando
        </a>
    </div>

    @if($pedidos->isEmpty())
    {{-- ── Estado vacío ── --}}
    <div class="flex flex-col items-center justify-center py-32 text-center">
        <div class="w-24 h-24 rounded-full bg-[#FFF3EE] flex items-center justify-center mb-6">
            <i class="fa-solid fa-box-open text-4xl text-[#E48F62]/40"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-700 mb-2">Aún no tienes pedidos</h2>
        <p class="text-gray-400 text-sm max-w-xs mb-8">
            Cuando realices una compra, tus pedidos aparecerán aquí.
        </p>
        <a href="{{ route('tienda') }}"
           class="bg-[#E48F62] hover:bg-[#d07070] text-white font-bold px-8 py-3
                  rounded-2xl transition flex items-center gap-2">
            <i class="fa-solid fa-store"></i> Explorar productos
        </a>
    </div>

    @else
    {{-- ── Lista de pedidos ── --}}
    <div class="space-y-5">
        @foreach($pedidos as $pedido)

        {{-- Estado config --}}
        @php
            $estadoConfig = match($pedido->estado) {
                'pagado'     => ['bg' => 'bg-green-50',  'text' => 'text-green-600',  'border' => 'border-green-100', 'icon' => 'fa-circle-check',    'label' => 'Pagado'],
                'enviado'    => ['bg' => 'bg-blue-50',   'text' => 'text-blue-600',   'border' => 'border-blue-100',  'icon' => 'fa-truck',           'label' => 'Enviado'],
                'entregado'  => ['bg' => 'bg-teal-50',   'text' => 'text-teal-600',   'border' => 'border-teal-100',  'icon' => 'fa-house-circle-check','label' => 'Entregado'],
                'cancelado'  => ['bg' => 'bg-red-50',    'text' => 'text-red-500',    'border' => 'border-red-100',   'icon' => 'fa-circle-xmark',    'label' => 'Cancelado'],
                default      => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-600', 'border' => 'border-yellow-100','icon' => 'fa-clock',           'label' => 'Pendiente'],
            };
        @endphp

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden
                    hover:shadow-md transition-shadow duration-300"
             id="pedido-{{ $pedido->Id }}">

            {{-- Cabecera del pedido --}}
            <div class="px-6 py-4 flex items-center justify-between flex-wrap gap-3
                        border-b border-gray-50 bg-[#FAFAF8]">
                <div class="flex items-center gap-4 flex-wrap">
                    <div>
                        <span class="text-xs text-gray-400 font-medium uppercase tracking-wide block">
                            Pedido
                        </span>
                        <span class="font-extrabold text-[#E48F62] text-lg">
                            #{{ str_pad($pedido->Id, 6, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                    <div class="h-8 w-px bg-gray-100 hidden sm:block"></div>
                    <div>
                        <span class="text-xs text-gray-400 font-medium uppercase tracking-wide block">
                            Fecha
                        </span>
                        <span class="text-sm font-semibold text-gray-700">
                            {{ \Carbon\Carbon::parse($pedido->fr)->format('d/m/Y') }}
                        </span>
                    </div>
                    @if($pedido->pagado_en)
                    <div class="h-8 w-px bg-gray-100 hidden sm:block"></div>
                    <div>
                        <span class="text-xs text-gray-400 font-medium uppercase tracking-wide block">
                            Pagado el
                        </span>
                        <span class="text-sm font-semibold text-gray-700">
                            {{ \Carbon\Carbon::parse($pedido->pagado_en)->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    {{-- Badge de estado --}}
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5
                                 rounded-full border {{ $estadoConfig['bg'] }} {{ $estadoConfig['text'] }}
                                 {{ $estadoConfig['border'] }}">
                        <i class="fa-solid {{ $estadoConfig['icon'] }} text-[10px]"></i>
                        {{ $estadoConfig['label'] }}
                    </span>

                    {{-- Toggle detalles --}}
                    <button onclick="toggleDetalle({{ $pedido->Id }})"
                            class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-[#FFF3EE] hover:text-[#E48F62]
                                   text-gray-400 flex items-center justify-center transition cursor-pointer"
                            id="toggle-btn-{{ $pedido->Id }}"
                            title="Ver detalles">
                        <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300"
                           id="toggle-icon-{{ $pedido->Id }}"></i>
                    </button>
                </div>
            </div>

            {{-- Preview productos (siempre visible) --}}
            <div class="px-6 py-4 flex items-center justify-between flex-wrap gap-4">
                {{-- Miniaturas --}}
                <div class="flex items-center gap-2 flex-wrap">
                    @foreach($pedido->detalles->take(4) as $d)
                    @php $img = $d->producto?->imagenes->first() ?? null; @endphp
                    <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-50 border border-gray-100
                                flex-shrink-0 relative group">
                        @if($img)
                            <img src="{{ asset('images/' . $img->ruta) }}"
                                 alt="{{ $d->nombre_producto }}"
                                 class="w-full h-full object-contain">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fa-solid fa-image text-gray-200 text-xs"></i>
                            </div>
                        @endif
                        @if($d->tipo_producto === 'servicio')
                        <span class="absolute bottom-0 right-0 w-4 h-4 bg-blue-500 rounded-tl-lg
                                     flex items-center justify-center">
                            <i class="fa-solid fa-wrench text-white text-[7px]"></i>
                        </span>
                        @endif
                    </div>
                    @endforeach

                    @if($pedido->detalles->count() > 4)
                    <div class="w-12 h-12 rounded-xl bg-gray-100 border border-gray-100
                                flex items-center justify-center">
                        <span class="text-xs font-bold text-gray-500">
                            +{{ $pedido->detalles->count() - 4 }}
                        </span>
                    </div>
                    @endif

                    <div class="ml-2">
                        <p class="text-xs text-gray-400">
                            {{ $pedido->detalles->count() }}
                            {{ $pedido->detalles->count() === 1 ? 'producto' : 'productos' }}
                        </p>
                        <p class="text-xs text-gray-500 font-medium line-clamp-1">
                            {{ $pedido->detalles->pluck('nombre_producto')->join(', ') }}
                        </p>
                    </div>
                </div>

                {{-- Total --}}
                <div class="text-right">
                    <span class="text-xs text-gray-400 block">Total pagado</span>
                    <span class="text-xl font-extrabold text-[#E48F62]">
                        ${{ number_format($pedido->total, 2) }} MXN
                    </span>
                </div>
            </div>

            {{-- Detalle expandible --}}
            <div id="detalle-{{ $pedido->Id }}"
                 class="hidden border-t border-dashed border-gray-100">

                {{-- Productos del pedido --}}
                <div class="px-6 py-5">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-4">
                        Productos
                    </p>
                    <div class="space-y-3">
                        @foreach($pedido->detalles as $d)
                        @php $img = $d->producto?->imagenes->first() ?? null; @endphp
                        <div class="flex items-center gap-4 py-3 border-b border-gray-50 last:border-0">
                            <div class="w-14 h-14 rounded-xl overflow-hidden bg-gray-50 flex-shrink-0">
                                @if($img)
                                    <img src="{{ asset('images/' . $img->ruta) }}"
                                         alt="{{ $d->nombre_producto }}"
                                         class="w-full h-full object-contain">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                        <i class="fa-solid fa-image text-gray-300"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sm text-gray-800 line-clamp-1">
                                    {{ $d->nombre_producto }}
                                </p>
                                <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                                    @if($d->tipo_producto === 'servicio')
                                        <span class="text-[10px] font-bold text-blue-500 bg-blue-50
                                                     px-2 py-0.5 rounded-full">Servicio</span>
                                        @if($d->fecha_servicio)
                                        <span class="text-[10px] text-gray-400">
                                            <i class="fa-solid fa-calendar-days mr-1 text-blue-400"></i>
                                            {{ \Carbon\Carbon::parse($d->fecha_servicio)->format('d/m/Y') }}
                                        </span>
                                        @endif
                                    @else
                                        <span class="text-[10px] font-bold text-[#E48F62] bg-[#FFF3EE]
                                                     px-2 py-0.5 rounded-full">Producto</span>
                                        <span class="text-[10px] text-gray-400">x{{ $d->cantidad }}</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    ${{ number_format($d->precio_unitario, 2) }} c/u
                                </p>
                            </div>
                            <span class="font-extrabold text-[#E48F62] text-sm flex-shrink-0">
                                ${{ number_format($d->subtotal, 2) }}
                            </span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Resumen de totales --}}
                    <div class="mt-4 pt-4 border-t border-dashed border-gray-100 space-y-1.5">
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Subtotal</span>
                            <span>${{ number_format($pedido->total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Envío</span>
                            <span class="text-green-500 font-semibold">Gratis</span>
                        </div>
                        <div class="flex justify-between font-extrabold text-gray-800 text-base
                                    pt-2 border-t border-gray-100">
                            <span>Total</span>
                            <span class="text-[#E48F62]">${{ number_format($pedido->total, 2) }} MXN</span>
                        </div>
                    </div>
                </div>

                {{-- Datos de envío --}}
                <div class="px-6 pb-5 grid sm:grid-cols-2 gap-4">
                    <div class="bg-[#FAFAF8] rounded-2xl p-4 border border-gray-100">
                        <p class="text-xs font-bold text-[#E48F62] uppercase tracking-wide mb-3">
                            <i class="fa-solid fa-location-dot mr-1"></i> Datos de envío
                        </p>
                        <p class="text-sm font-bold text-gray-800">{{ $pedido->nombre_envio }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $pedido->email_envio }}</p>
                        @if($pedido->telefono_envio)
                        <p class="text-xs text-gray-500">{{ $pedido->telefono_envio }}</p>
                        @endif
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $pedido->calle_envio }}
                            @if($pedido->colonia_envio), {{ $pedido->colonia_envio }}@endif
                            @if($pedido->ciudad_envio), {{ $pedido->ciudad_envio }}@endif
                            @if($pedido->cp_envio) CP {{ $pedido->cp_envio }}@endif
                        </p>
                    </div>

                    <div class="bg-[#FAFAF8] rounded-2xl p-4 border border-gray-100">
                        <p class="text-xs font-bold text-[#E48F62] uppercase tracking-wide mb-3">
                            <i class="fa-brands fa-stripe mr-1"></i> Pago
                        </p>
                        <p class="text-xs text-gray-500">ID de transacción</p>
                        <p class="text-xs font-mono text-gray-700 break-all mt-0.5">
                            {{ $pedido->stripe_payment_id ?? '—' }}
                        </p>
                        <div class="mt-3 flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full
                                {{ $pedido->estado === 'pagado' || $pedido->estado === 'enviado' || $pedido->estado === 'entregado'
                                    ? 'bg-green-400' : 'bg-yellow-400' }}">
                            </div>
                            <span class="text-xs font-semibold text-gray-600 capitalize">
                                {{ $estadoConfig['label'] }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        @endforeach
    </div>

    {{-- Paginación --}}
    @if($pedidos->hasPages())
    <div class="mt-10 flex justify-center">
        {{ $pedidos->links() }}
    </div>
    @endif

    @endif

</div>
</div>

@push('scripts')
<script>
function toggleDetalle(pedidoId) {
    const detalle = document.getElementById('detalle-' + pedidoId);
    const icon    = document.getElementById('toggle-icon-' + pedidoId);
    const isOpen  = !detalle.classList.contains('hidden');

    if (isOpen) {
        detalle.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    } else {
        detalle.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    }
}

// Si se llega desde checkout/exito con ?pedido=X, abrir ese pedido automáticamente
const params = new URLSearchParams(window.location.search);
const pedidoAuto = params.get('abrir');
if (pedidoAuto) {
    setTimeout(() => {
        const card = document.getElementById('pedido-' + pedidoAuto);
        if (card) {
            card.scrollIntoView({ behavior: 'smooth', block: 'center' });
            toggleDetalle(pedidoAuto);
        }
    }, 400);
}
</script>
@endpush

@endsection
