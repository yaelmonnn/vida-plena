@extends('layout.app')

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     TIENDA — VIDA PLENA
     Fuente: Google Fonts Outfit (display) + Lato (body)
     Paleta: coral #E48F62 · rose #E28987 · amber #ffbb51 · mint #83d77c
══════════════════════════════════════════════════════════════ --}}

<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;900&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

<style>
    :root {
        --coral:   #E48F62;
        --rose:    #E28987;
        --amber:   #ffbb51;
        --mint:    #83d77c;
        --ink:     #1a1a2e;
        --muted:   #6b7280;
        --surface: #fafaf8;
    }

    .tienda-body { font-family: 'Lato', sans-serif; background: var(--surface); }
    .tienda-display { font-family: 'Outfit', sans-serif; }

    /* Hero strip */
    .tienda-hero {
        background: linear-gradient(135deg, var(--ink) 0%, #16213e 60%, #0f3460 100%);
        position: relative;
        overflow: hidden;
    }
    .tienda-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 60% 80% at 80% 50%, rgba(228,143,98,.18) 0%, transparent 70%),
            radial-gradient(ellipse 40% 60% at 20% 80%, rgba(226,137,135,.12) 0%, transparent 60%);
    }
    .tienda-hero::after {
        content: '';
        position: absolute;
        bottom: -1px; left: 0; right: 0;
        height: 60px;
        background: var(--surface);
        clip-path: ellipse(55% 100% at 50% 100%);
    }

    /* Sidebar */
    .filter-card {
        background: #fff;
        border-radius: 1.5rem;
        box-shadow: 0 4px 24px rgba(26,26,46,.06);
        border: 1px solid rgba(228,143,98,.08);
    }
    .filter-section { border-bottom: 1px solid #f0ede8; }
    .filter-section:last-child { border-bottom: none; }

    /* Range dual thumb */
    .range-wrap { position: relative; height: 6px; }
    .range-wrap input[type=range] {
        position: absolute; width: 100%; pointer-events: none;
        -webkit-appearance: none; appearance: none;
        height: 6px; background: transparent;
    }
    .range-track {
        position: absolute; inset: 0;
        border-radius: 9999px;
        background: #e5e0d8;
    }
    .range-fill {
        position: absolute; height: 6px;
        border-radius: 9999px;
        background: linear-gradient(90deg, var(--coral), var(--rose));
    }
    input[type=range]::-webkit-slider-thumb {
        pointer-events: all; -webkit-appearance: none;
        width: 18px; height: 18px; border-radius: 50%;
        background: #fff; border: 2px solid var(--coral);
        box-shadow: 0 2px 8px rgba(228,143,98,.35);
        cursor: pointer; transition: transform .15s;
    }
    input[type=range]::-webkit-slider-thumb:hover { transform: scale(1.2); }

    /* Product card */
    .pcard {
        background: #fff;
        border-radius: 1.25rem;
        border: 1px solid #f0ede8;
        overflow: hidden;
        transition: box-shadow .25s, transform .25s;
    }
    .pcard:hover {
        box-shadow: 0 16px 48px rgba(228,143,98,.18);
        transform: translateY(-4px);
    }
    .pcard-img { overflow: hidden; background: #f5f0ec; }
    .pcard-img img { transition: transform .5s ease; }
    .pcard:hover .pcard-img img { transform: scale(1.07); }

    /* List card */
    .pcard-list {
        background: #fff;
        border-radius: 1.25rem;
        border: 1px solid #f0ede8;
        overflow: hidden;
        display: flex;
        transition: box-shadow .25s, transform .2s;
    }
    .pcard-list:hover {
        box-shadow: 0 8px 32px rgba(228,143,98,.15);
        transform: translateX(4px);
    }

    /* Badges */
    .badge-producto { background: linear-gradient(135deg, var(--coral), #d4784a); color: #fff; }
    .badge-servicio { background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: #fff; }
    .badge-stock    { background: #dcfce7; color: #15803d; }
    .badge-stock-low{ background: #fef9c3; color: #854d0e; }
    .badge-out      { background: #fee2e2; color: #991b1b; }

    /* Stars */
    .star-filled { color: var(--amber); }
    .star-empty  { color: #d1cdc7; }

    /* Btn principal */
    .btn-coral {
        background: linear-gradient(135deg, var(--coral) 0%, #d4784a 100%);
        color: #fff; border: none; border-radius: .875rem;
        font-weight: 700; font-family: 'Outfit', sans-serif;
        transition: filter .2s, transform .15s;
    }
    .btn-coral:hover { filter: brightness(1.1); transform: translateY(-1px); }

    /* Sort/view bar */
    .toolbar {
        background: #fff;
        border-radius: 1rem;
        border: 1px solid #f0ede8;
    }

    /* Pagination */
    .page-btn {
        width: 38px; height: 38px; border-radius: .625rem;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Outfit', sans-serif; font-weight: 600;
        font-size: .875rem; border: 1px solid #e5e0d8;
        background: #fff; color: var(--ink);
        transition: all .15s; cursor: pointer;
    }
    .page-btn:hover { border-color: var(--coral); color: var(--coral); }
    .page-btn.active {
        background: linear-gradient(135deg, var(--coral), #d4784a);
        color: #fff; border-color: transparent;
        box-shadow: 0 4px 12px rgba(228,143,98,.4);
    }
    .page-btn.disabled { opacity: .4; cursor: not-allowed; pointer-events: none; }

    /* Checkbox custom */
    .check-custom input[type=checkbox] {
        accent-color: var(--coral);
        width: 1rem; height: 1rem; border-radius: .25rem;
    }

    /* Tag pills activos */
    .active-filter-pill {
        background: #fff4ef;
        border: 1px solid rgba(228,143,98,.3);
        color: var(--coral);
        border-radius: 9999px;
        font-size: .75rem; font-weight: 600;
        padding: .2rem .75rem;
        display: inline-flex; align-items: center; gap: .35rem;
    }

    /* Scroll reveal */
    .reveal { opacity: 0; transform: translateY(30px); transition: all .7s ease; }
    .reveal.active { opacity: 1; transform: none; }
</style>

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
