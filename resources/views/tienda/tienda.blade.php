@extends('layout.app')

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     TIENDA — VIDA PLENA
     Fuente: Google Fonts Outfit (display) + Lato (body)
     Paleta: coral #E48F62 · rose #E28987 · amber #ffbb51 · mint #83d77c
══════════════════════════════════════════════════════════════ --}}


@include('partials.tienda-styles')

<div class="tienda-body">

{{-- ─── HERO STRIP ─── --}}
<div class="tienda-hero py-14 md:py-20 relative">
    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">

            <div>
                <p class="tienda-display text-sm font-semibold text-[#E48F62] uppercase tracking-widest mb-2">
                    <i class="fa-solid fa-store mr-1"></i> Tienda completa
                </p>
                <h1 class="tienda-display text-4xl md:text-6xl font-black text-white leading-none">
                    Todo lo que<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#E48F62] to-[#E28987]">
                        necesitas
                    </span>
                </h1>
                <p class="text-white/60 mt-4 text-lg max-w-md">
                    Productos, dispositivos y servicios especializados para el bienestar.
                </p>
            </div>

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-white/50 text-sm font-medium">
                <a href="{{ route('inicio') }}" class="hover:text-white transition">
                    <i class="fa-solid fa-house text-xs"></i>
                </a>
                <i class="fa-solid fa-chevron-right text-xs"></i>
                <span class="text-white/80">Tienda</span>
            </nav>

        </div>
    </div>
</div>

{{-- ─── COMPONENTE LIVEWIRE ─── --}}
<livewire:tienda />

</div>
@endsection
