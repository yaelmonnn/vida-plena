@extends('layout.app')

@section('content')
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;900&family=Lato:ital,wght@0,300;0,400;0,700;1,300&display=swap"
        rel="stylesheet">

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(-3deg); }
            50%       { transform: translateY(-18px) rotate(3deg); }
        }
        @keyframes pulse-ring {
            0%   { transform: scale(0.85); opacity: .6; }
            70%  { transform: scale(1.15); opacity: 0; }
            100% { transform: scale(0.85); opacity: 0; }
        }
        @keyframes fade-up {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .float      { animation: float 5s ease-in-out infinite; }
        .pulse-ring { animation: pulse-ring 2.4s ease-out infinite; }

        .fade-up-1 { animation: fade-up .6s ease both; }
        .fade-up-2 { animation: fade-up .6s .15s ease both; }
        .fade-up-3 { animation: fade-up .6s .30s ease both; }
        .fade-up-4 { animation: fade-up .6s .45s ease both; }

        .leaf-bg {
            background-image:
                radial-gradient(circle at 15% 25%, #FFF3EC 0%, transparent 50%),
                radial-gradient(circle at 85% 75%, #FFF3EC 0%, transparent 50%);
        }
    </style>

    <section class="min-h-screen leaf-bg bg-[#fafaf8] flex items-center justify-center px-4 pt-24 pb-16">

        {{-- Decorative blobs --}}
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -top-20 -left-20 w-96 h-96 rounded-full bg-[#E48F62]/8"></div>
            <div class="absolute -bottom-20 -right-20 w-80 h-80 rounded-full bg-[#E48F62]/6"></div>
            <i class="fa-solid fa-leaf absolute top-24 right-12 text-[#E48F62]/10 text-6xl rotate-12"></i>
            <i class="fa-solid fa-seedling absolute bottom-24 left-10 text-[#E48F62]/10 text-5xl -rotate-12"></i>
        </div>

        <div class="relative max-w-2xl mx-auto text-center">

            {{-- Floating 404 icon --}}
            <div class="fade-up-1 relative inline-flex items-center justify-center mb-10">
                {{-- Pulse rings --}}
                <span class="pulse-ring absolute w-52 h-52 rounded-full border-2 border-[#E48F62]/30"></span>
                <span class="pulse-ring absolute w-52 h-52 rounded-full border-2 border-[#E48F62]/20"
                      style="animation-delay:.4s"></span>

                <div class="float relative w-44 h-44 bg-[#FFF3EC] rounded-full flex items-center justify-center shadow-xl shadow-[#E48F62]/20">
                    <i class="fa-solid fa-map-location-dot text-6xl text-[#E48F62]"></i>
                </div>
            </div>

            {{-- 404 number --}}
            <p class="fade-up-1 font-black text-[9rem] leading-none text-[#E48F62]/15 select-none -mb-8">
                404
            </p>

            {{-- Headline --}}
            <h1 class="fade-up-2 text-4xl sm:text-5xl font-black text-gray-800 leading-tight mb-4">
                ¡Ups! Esta página<br>
                <span class="text-[#E48F62]">no existe.</span>
            </h1>

            {{-- Subtitle --}}
            <p class="fade-up-3 text-gray-400 text-lg leading-relaxed max-w-md mx-auto mb-10">
                Parece que te perdiste en el camino hacia el bienestar.
                La página que buscas no se encuentra disponible o fue movida.
            </p>

            {{-- Actions --}}
            <div class="fade-up-4 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('inicio') }}"
                   class="inline-flex items-center justify-center gap-2 bg-[#E48F62] hover:bg-[#d07a4e]
                          text-white font-bold px-8 py-3.5 rounded-2xl transition shadow-lg shadow-[#E48F62]/30">
                    <i class="fa-solid fa-house text-sm"></i>
                    Ir al inicio
                </a>
                <a href="{{ route('tienda') }}"
                   class="inline-flex items-center justify-center gap-2 border-2 border-[#E48F62]
                          text-[#E48F62] hover:bg-[#FFF3EC] font-bold px-8 py-3.5 rounded-2xl transition">
                    <i class="fa-solid fa-store text-sm"></i>
                    Ver tienda
                </a>
            </div>

            {{-- Quick links --}}
            <div class="fade-up-4 mt-12 pt-8 border-t border-gray-100">
                <p class="text-gray-400 text-sm mb-4 font-semibold uppercase tracking-widest">
                    O visita alguna de estas secciones
                </p>
                <div class="flex flex-wrap justify-center gap-3">
                    @foreach ([
                        ['route' => 'nosotros',  'icon' => 'fa-solid fa-users',   'label' => 'Nosotros'],
                        ['route' => 'contacto',  'icon' => 'fa-solid fa-envelope', 'label' => 'Contacto'],
                    ] as $link)
                        <a href="{{ route($link['route']) }}"
                           class="inline-flex items-center gap-1.5 bg-white border border-gray-100 hover:border-[#E48F62]/40
                                  hover:bg-[#FFF3EC] text-gray-500 hover:text-[#E48F62] text-sm font-semibold
                                  px-4 py-2 rounded-xl transition shadow-sm">
                            <i class="{{ $link['icon'] }} text-xs"></i>
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

        </div>
    </section>
@endsection
