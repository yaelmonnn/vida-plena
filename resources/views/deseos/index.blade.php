{{-- resources/views/deseos/index.blade.php --}}
@extends('layout.app')

@section('content')
<div class="min-h-screen bg-[#fafaf8] pt-28 pb-16 px-4">
<div class="max-w-6xl mx-auto">

    {{-- ── Encabezado ── --}}
    <div class="mb-10 flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-4xl font-extrabold text-gray-800">
                Mis <span class="text-[#E28987]">Deseos</span>
                <i class="fa-solid fa-heart text-[#E28987] text-3xl ml-2 align-middle"></i>
            </h1>
            <p class="text-gray-400 text-sm mt-1">
                {{ $deseos->count() }} {{ $deseos->count() === 1 ? 'producto guardado' : 'productos guardados' }}
            </p>
        </div>
        <a href="{{ route('tienda') }}"
            class="flex items-center gap-2 text-sm text-[#E48F62] hover:underline font-semibold">
            <i class="fa-solid fa-arrow-left text-xs"></i> Seguir explorando
        </a>
    </div>

    @if($deseos->isEmpty())
    {{-- ── Estado vacío ── --}}
    <div class="flex flex-col items-center justify-center py-32 text-center">
        <div class="w-24 h-24 rounded-full bg-[#FFF0F0] flex items-center justify-center mb-6">
            <i class="fa-regular fa-heart text-4xl text-[#E28987]/40"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-700 mb-2">Aún no tienes deseos guardados</h2>
        <p class="text-gray-400 text-sm max-w-xs mb-8">
            Guarda tus productos favoritos para encontrarlos fácilmente después.
        </p>
        <a href="{{ route('tienda') }}"
            class="bg-[#E28987] hover:bg-[#d07070] text-white font-bold px-8 py-3
                   rounded-2xl transition flex items-center gap-2 cursor-pointer">
            <i class="fa-solid fa-store"></i> Explorar productos
        </a>
    </div>

    @else
    {{-- ── Grid de deseos ── --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="deseos-grid">

        @foreach($deseos as $p)

        <div class="bg-white rounded-3xl shadow-md hover:shadow-xl transition-all duration-300 group
                    flex flex-col overflow-hidden border border-gray-50"
             id="deseo-card-{{ $p->producto_id }}">

            {{-- Imagen --}}
            <div class="relative h-48 bg-white overflow-hidden">
                @php $img = $p->imagenes[0] ?? null; @endphp
                @if($img)
                    <img src="{{ asset('images/' . $img->ruta) }}"
                         alt="{{ $img->alt_text ?? $p->nombre }}"
                         class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-50">
                        <i class="fa-solid fa-image text-3xl text-gray-200"></i>
                    </div>
                @endif

                {{-- Badge tipo --}}
                <span class="absolute top-3 left-3 text-white text-[10px] font-bold px-2.5 py-0.5
                             rounded-full uppercase tracking-wide shadow
                             {{ $p->tipo === 'servicio' ? 'bg-blue-500' : 'bg-[#E48F62]' }}">
                    {{ $p->tipo }}
                </span>

                {{-- Botón quitar --}}
                <button
                    onclick="quitarDeseo({{ $p->producto_id }}, this)"
                    class="absolute top-3 right-3 bg-white/90 hover:bg-red-50 text-[#E28987]
                           hover:text-red-500 w-8 h-8 rounded-full flex items-center justify-center
                           shadow transition-all duration-200 cursor-pointer hover:scale-110 active:scale-95"
                    title="Quitar de favoritos">
                    <i class="fa-solid fa-heart text-sm"></i>
                </button>
            </div>

            {{-- Info --}}
            <div class="p-4 flex flex-col gap-2 flex-1">

                <span class="text-[10px] font-bold text-[#E28987] uppercase tracking-wide">
                    {{ $p->categoria }}
                </span>

                <h3 class="font-bold text-gray-800 text-sm line-clamp-2 leading-snug">
                    {{ $p->nombre }}
                </h3>

                <div class="flex items-center gap-1">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-solid fa-star text-xs
                           {{ $i <= $p->calificacion ? 'text-[#ffbb51]' : 'text-gray-200' }}"></i>
                    @endfor
                    <span class="text-xs text-gray-400 ml-1">{{ $p->calificacion }}.0</span>
                </div>

                <div class="flex items-center justify-between mt-auto pt-2 border-t border-gray-50">
                    <span class="text-lg font-extrabold text-[#E48F62]">
                        ${{ number_format($p->precio, 2) }}
                    </span>

                    @if($p->cantidad_disponible > 0)
                        <button
                            onclick="agregarAlCarrito({{ $p->producto_id }}, this)"
                            class="bg-[#E48F62] hover:bg-[#d07a4e] text-white text-xs font-bold
                                   px-3 py-2 rounded-xl flex items-center gap-1.5 transition-all duration-200
                                   cursor-pointer hover:scale-105 active:scale-95 shadow hover:shadow-md">
                            <i class="fa-solid fa-cart-plus"></i>
                            {{ $p->tipo === 'servicio' ? 'Contratar' : 'Al carrito' }}
                        </button>
                    @else
                        <span class="text-xs text-red-400 font-semibold bg-red-50 px-3 py-1 rounded-full">
                            Agotado
                        </span>
                    @endif
                </div>
            </div>
        </div>

        @endforeach
    </div>
    @endif

</div>
</div>

@push('scripts')
<script>
function quitarDeseo(productoId, btnEl) {
    Swal.fire({
        title: '¿Quitar de favoritos?',
        text: 'Se eliminará este producto de tu lista de deseos.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, quitar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#E28987',
        cancelButtonColor: '#6b7280',
        reverseButtons: true,
    }).then(result => {
        if (!result.isConfirmed) return;

        fetch(`/deseos/${productoId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                const card = document.getElementById('deseo-card-' + productoId);
                if (card) {
                    card.style.transition = 'all 0.4s ease';
                    card.style.opacity    = '0';
                    card.style.transform  = 'scale(0.9)';
                    setTimeout(() => card.remove(), 400);
                }

                const badge = document.getElementById('deseos-badge');
                if (badge) {
                    badge.textContent = data.total_deseos;
                    if (data.total_deseos === 0) badge.classList.add('hidden');
                }

                if (window.__deseos) window.__deseos.delete(Number(productoId));

                const Toast = Swal.mixin({
                    toast: true, position: 'top-end',
                    showConfirmButton: false, timer: 2000,
                });
                Toast.fire({ icon: 'info', title: 'Eliminado de tus deseos' });
            }
        })
        .catch(() => {
            Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo eliminar.', confirmButtonColor: '#E28987' });
        });
    });
}
</script>
@endpush

@endsection
