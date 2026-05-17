{{-- resources/views/checkout/exito.blade.php --}}
@extends('layout.app')

@section('content')
<div class="min-h-screen bg-[#fafaf8] pt-28 pb-20 px-4 flex items-center justify-center">
<div class="max-w-lg w-full text-center">

    {{-- Animación éxito --}}
    <div class="mb-8 flex justify-center">
        <div class="w-28 h-28 rounded-full bg-green-50 border-4 border-green-100
                    flex items-center justify-center animate-bounce-slow">
            <i class="fa-solid fa-check text-4xl text-green-500"></i>
        </div>
    </div>

    <h1 class="text-3xl font-extrabold text-gray-800 mb-2">
        ¡Pedido confirmado!
    </h1>
    <p class="text-gray-400 mb-8">
        Gracias por tu compra. Recibirás un correo con los detalles de tu pedido.
    </p>

    @if($pedido)
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-8 text-left">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wide">Número de pedido</span>
            <span class="font-extrabold text-[#E48F62] text-lg">#{{ str_pad($pedido->Id, 6, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="space-y-2">
            @foreach($pedido->detalles as $d)
            <div class="flex justify-between text-sm text-gray-700 py-2 border-b border-gray-50 last:border-0">
                <span class="font-semibold">{{ $d->nombre_producto }}</span>
                <span class="font-bold text-[#E48F62]">${{ number_format($d->subtotal, 2) }}</span>
            </div>
            @endforeach
        </div>
        <div class="mt-4 pt-4 border-t border-dashed border-gray-100 flex justify-between font-extrabold text-gray-800">
            <span>Total pagado</span>
            <span class="text-[#E48F62]">${{ number_format($pedido->total, 2) }} MXN</span>
        </div>
    </div>
    @endif

    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ route('tienda') }}"
           class="bg-[#E48F62] hover:bg-[#d07a4e] text-white font-bold px-8 py-3 rounded-2xl
                  transition flex items-center justify-center gap-2">
            <i class="fa-solid fa-store"></i> Seguir comprando
        </a>
        <a href="{{ route('perfil.pedidos') }}"
           class="border-2 border-[#E48F62] text-[#E48F62] hover:bg-[#FFF3EE] font-bold px-8 py-3
                  rounded-2xl transition flex items-center justify-center gap-2">
            <i class="fa-solid fa-box"></i> Mis pedidos
        </a>
    </div>

</div>
</div>
@endsection
