@extends('layout.app')

@section('content')
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;900&family=Lato:ital,wght@0,300;0,400;0,700;1,300&display=swap"
        rel="stylesheet">

    {{-- ── Hero ── --}}
    <section class="min-h-[60vh] bg-[#fafaf8] flex items-center pt-28 pb-16 px-4">
        <div class="max-w-5xl mx-auto flex flex-col lg:flex-row items-center gap-12">

            {{-- Texto --}}
            <div class="flex-1 reveal">
                <span class="inline-block bg-[#FFF3EC] text-[#E48F62] text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-4">
                    Nuestra historia
                </span>
                <h1 class="text-5xl font-black text-gray-800 leading-tight mb-6">
                    Vivir mejor, <br>
                    <span class="text-[#E48F62]">cada día.</span>
                </h1>
                <p class="text-gray-500 text-lg leading-relaxed max-w-lg">
                    En <strong class="text-gray-700">Vida Plena</strong> creemos que el bienestar no es un lujo,
                    es un estilo de vida al alcance de todos. Desde 2018 acompañamos a nuestros clientes
                    con productos y servicios pensados para su salud integral.
                </p>
                <a href="{{ route('tienda') }}"
                    class="mt-8 inline-flex items-center gap-2 bg-[#E48F62] hover:bg-[#d07a4e]
                           text-white font-bold px-8 py-3.5 rounded-2xl transition">
                    <i class="fa-solid fa-store text-sm"></i> Conoce nuestros productos
                </a>
            </div>

            {{-- Imagen / ilustración --}}
            <div class="flex-1 flex justify-center reveal">
                <div class="relative w-72 h-72 lg:w-96 lg:h-96">
                    <div class="absolute inset-0 bg-[#FFF3EC] rounded-full"></div>
                    <img src="{{ asset('images/nosotros-hero.png') }}" alt="Equipo Vida Plena"
                        class="relative w-full h-full object-contain drop-shadow-md"
                        onerror="this.style.display='none'">
                    {{-- Fallback decorativo si no hay imagen --}}
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fa-solid fa-leaf text-7xl text-[#E48F62]/20"></i>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ── Valores ── --}}
    <section class="bg-white py-20 px-4">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-12 reveal">
                <span class="text-[#E48F62] text-xs font-bold uppercase tracking-widest">Lo que nos mueve</span>
                <h2 class="text-3xl font-black text-gray-800 mt-2">Nuestros valores</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach ([
                    ['icon' => 'fa-solid fa-heart-pulse',   'title' => 'Salud integral',    'desc'  => 'Nos enfocamos en el bienestar físico, mental y emocional de cada persona.'],
                    ['icon' => 'fa-solid fa-seedling',       'title' => 'Productos naturales','desc'  => 'Seleccionamos ingredientes de origen natural, sin comprometer la calidad.'],
                    ['icon' => 'fa-solid fa-shield-halved',  'title' => 'Confianza',          'desc'  => 'Cada producto y servicio pasa por un riguroso control de calidad.'],
                    ['icon' => 'fa-solid fa-users',          'title' => 'Comunidad',          'desc'  => 'Construimos una red de personas comprometidas con vivir mejor.'],
                    ['icon' => 'fa-solid fa-earth-americas', 'title' => 'Sostenibilidad',     'desc'  => 'Cuidamos el planeta en cada decisión que tomamos.'],
                    ['icon' => 'fa-solid fa-star',           'title' => 'Excelencia',         'desc'  => 'Buscamos superar las expectativas en cada interacción.'],
                ] as $valor)
                    <div class="bg-[#fafaf8] rounded-2xl border border-gray-100 p-6 hover:shadow-md transition reveal group">
                        <div class="w-12 h-12 rounded-xl bg-[#FFF3EC] flex items-center justify-center mb-4 group-hover:bg-[#E48F62] transition">
                            <i class="{{ $valor['icon'] }} text-xl text-[#E48F62] group-hover:text-white transition"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 text-base mb-1">{{ $valor['title'] }}</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">{{ $valor['desc'] }}</p>
                    </div>
                @endforeach

            </div>
        </div>
    </section>

    {{-- ── Estadísticas ── --}}
    <section class="bg-[#E48F62] py-16 px-4">
        <div class="max-w-5xl mx-auto grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            @foreach ([
                ['num' => '6+',   'label' => 'Años de experiencia'],
                ['num' => '5K+',  'label' => 'Clientes satisfechos'],
                ['num' => '120+', 'label' => 'Productos disponibles'],
                ['num' => '4.9',  'label' => 'Calificación promedio'],
            ] as $stat)
                <div class="reveal">
                    <p class="text-4xl font-black text-white">{{ $stat['num'] }}</p>
                    <p class="text-white/70 text-sm mt-1 font-semibold">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ── Equipo ── --}}
    <section class="bg-[#fafaf8] py-20 px-4">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-12 reveal">
                <span class="text-[#E48F62] text-xs font-bold uppercase tracking-widest">Las personas detrás</span>
                <h2 class="text-3xl font-black text-gray-800 mt-2">Nuestro equipo</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ([
                    ['nombre' => 'Ana García',    'puesto' => 'Fundadora & CEO',          'img' => 'equipo-1.jpg'],
                    ['nombre' => 'Luis Martínez', 'puesto' => 'Director de Operaciones',  'img' => 'equipo-2.jpg'],
                    ['nombre' => 'Sara López',    'puesto' => 'Especialista en Bienestar','img' => 'equipo-3.jpg'],
                ] as $miembro)
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-md transition reveal group">
                        <div class="h-48 bg-[#FFF3EC] flex items-center justify-center overflow-hidden">
                            <img src="{{ asset('images/' . $miembro['img']) }}" alt="{{ $miembro['nombre'] }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                onerror="this.parentElement.innerHTML='<i class=\'fa-solid fa-user text-5xl text-[#E48F62]/30\'></i>'">
                        </div>
                        <div class="p-5">
                            <h4 class="font-bold text-gray-800">{{ $miembro['nombre'] }}</h4>
                            <p class="text-[#E48F62] text-sm font-semibold">{{ $miembro['puesto'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── CTA final ── --}}
    <section class="bg-white py-20 px-4">
        <div class="max-w-2xl mx-auto text-center reveal">
            <h2 class="text-3xl font-black text-gray-800 mb-4">¿Listo para empezar tu camino?</h2>
            <p class="text-gray-400 mb-8">
                Descubre todos nuestros productos y servicios diseñados para ayudarte a vivir con más energía y bienestar.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('tienda') }}"
                    class="bg-[#E48F62] hover:bg-[#d07a4e] text-white font-bold px-8 py-3.5 rounded-2xl transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-store text-sm"></i> Ver tienda
                </a>
                <a href="{{ route('contacto') }}"
                    class="border-2 border-[#E48F62] text-[#E48F62] hover:bg-[#FFF3EC] font-bold px-8 py-3.5 rounded-2xl transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-envelope text-sm"></i> Contáctanos
                </a>
            </div>
        </div>
    </section>

@endsection
