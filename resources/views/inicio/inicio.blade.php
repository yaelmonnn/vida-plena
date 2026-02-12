@extends('layout.app')

@section('content')

<!-- HERO PREMIUM -->
<section class="relative overflow-hidden reveal">
    <div class="absolute inset-0 bg-gradient-to-r from-[#E48F62] via-[#E28987] to-[#ffbb51] opacity-90"></div>

    <div class="relative max-w-7xl mx-auto px-6 py-32 grid md:grid-cols-2 gap-12 items-center text-white">

        <div class="space-y-6">
            <h1 class="text-5xl md:text-6xl font-bold leading-tight">
                Autonomía, seguridad y tranquilidad para una
                <span class="text-[#83d77c]">VIDA PLENA</span>
            </h1>

            <p class="text-lg opacity-90">
                Creamos un ecosistema integral que combina hogar seguro,
                tecnología asistencial y cuidado humano profesional.
            </p>

            <div class="flex gap-5">
                <button class="bg-[#83d77c] px-8 py-4 rounded-2xl shadow-2xl hover:scale-105 transition duration-300 font-semibold">
                    Comprar ahora
                </button>

                <button class="bg-white text-[#E48F62] px-8 py-4 rounded-2xl hover:bg-[#ffbb51] hover:text-white transition duration-300 font-semibold">
                    Ver soluciones
                </button>
            </div>
        </div>

        <div class="relative">
            <img src="{{ asset('images/adulto-mayor.jpg') }}"
                 class="rounded-3xl shadow-2xl hover:scale-105 transition duration-500">
        </div>

    </div>
</section>




<!-- BENEFICIOS -->
<section class="py-20 bg-white reveal">
    <div class="max-w-7xl mx-auto px-6 text-center">

        <h2 class="text-3xl font-bold mb-14">
            Un ecosistema diseñado para el bienestar integral
        </h2>

        <div class="grid md:grid-cols-3 gap-10">

            <div class="p-10 rounded-3xl shadow-lg hover:shadow-2xl transition hover:-translate-y-3 bg-[#83d77c]/10">
                <div class="text-4xl mb-4">🏡</div>
                <h3 class="font-bold text-xl mb-3">Hogar Seguro</h3>
                <p class="text-gray-600">
                    Adaptaciones inteligentes para prevenir accidentes y mejorar movilidad.
                </p>
            </div>

            <div class="p-10 rounded-3xl shadow-lg hover:shadow-2xl transition hover:-translate-y-3 bg-[#E28987]/10">
                <div class="text-4xl mb-4">🩺</div>
                <h3 class="font-bold text-xl mb-3">Cuidado Profesional</h3>
                <p class="text-gray-600">
                    Rehabilitación y asistencia especializada coordinada.
                </p>
            </div>

            <div class="p-10 rounded-3xl shadow-lg hover:shadow-2xl transition hover:-translate-y-3 bg-[#ffbb51]/10">
                <div class="text-4xl mb-4">⌚</div>
                <h3 class="font-bold text-xl mb-3">Tecnología Asistencial</h3>
                <p class="text-gray-600">
                    Monitoreo remoto y soluciones inteligentes para tranquilidad familiar.
                </p>
            </div>

        </div>
    </div>
</section>


<!-- CATEGORÍAS DESTACADAS -->
<section class="py-20 bg-gradient-to-r from-[#E48F62]/10 to-[#E28987]/10 reveal">
    <div class="max-w-7xl mx-auto px-6">

        <h2 class="text-3xl font-bold mb-12 text-center">
            Explora nuestras soluciones
        </h2>

        <div class="grid md:grid-cols-3 gap-8">

            <div class="relative group rounded-3xl overflow-hidden shadow-xl">
                <img src="{{ asset('images/hogar-seguro.jpg') }}" class="w-full h-80 object-cover group-hover:scale-110 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-8">
                    <h3 class="text-white text-2xl font-bold">
                        Hogar Seguro y Adaptado
                    </h3>
                </div>
            </div>

            <div class="relative group rounded-3xl overflow-hidden shadow-xl">
                <img src="{{ asset('images/cuidado-rehabili.jpg') }}" class="w-full h-80 object-cover group-hover:scale-110 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-8">
                    <h3 class="text-white text-2xl font-bold">
                        Cuidado y Rehabilitación
                    </h3>
                </div>
            </div>

            <div class="relative group rounded-3xl overflow-hidden shadow-xl">
                <img src="{{ asset('images/hogar-tecno.jpg') }}" class="w-full h-80 object-cover group-hover:scale-110 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-8">
                    <h3 class="text-white text-2xl font-bold">
                        Tecnología Asistencial
                    </h3>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- PRODUCTOS -->
<x-filtros />


<!-- SECCIÓN CONFIANZA -->
<section class="py-20 bg-[#83d77c]/10 reveal">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">

        <div>
            <img src="{{ asset('images/adulto-mayor.jpg') }}"
                 class="rounded-3xl shadow-xl">
        </div>

        <div class="space-y-6">
            <h2 class="text-4xl font-bold">
                Tranquilidad para las familias,
                independencia para ellos.
            </h2>

            <p class="text-gray-600 text-lg">
                Coordinamos tecnología, productos y asistencia profesional
                para que el cuidado esté presente, incluso a la distancia.
            </p>

            <button class="bg-[#E48F62] text-white px-8 py-4 rounded-2xl shadow-lg hover:bg-[#E28987] transition">
                Descubrir cómo funciona
            </button>
        </div>

    </div>
</section>




<!-- CTA FINAL -->
<section class="py-20 bg-gradient-to-r from-[#E28987] to-[#E48F62] text-white text-center reveal">
    <div class="max-w-4xl mx-auto px-6 space-y-6">
        <h2 class="text-4xl font-bold">
            Empieza hoy a construir una VIDA PLENA
        </h2>

        <p class="text-lg opacity-90">
            Productos, tecnología y cuidado coordinado en un solo lugar.
        </p>

        <button class="bg-[#ffbb51] px-10 py-4 rounded-2xl font-bold hover:scale-105 transition">
            Explorar tienda
        </button>
    </div>
</section>

<section class="py-24 bg-gradient-to-r from-[#E28987]/10 to-[#E48F62]/10 reveal">
    <div class="max-w-7xl mx-auto px-6 text-center">

        <h2 class="text-3xl font-bold mb-16">
            Lo que dicen nuestras familias
        </h2>

        <div class="grid md:grid-cols-3 gap-10">

            <div class="bg-white p-8 rounded-3xl shadow-xl hover:-translate-y-3 transition">
                <x-rating-stars rating="5" />
                <p class="mt-4 text-gray-600">
                    "Desde que instalamos el sistema de monitoreo, dormimos tranquilos."
                </p>
                <div class="mt-4 font-semibold text-[#E48F62]">
                    — María G.
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-xl hover:-translate-y-3 transition">
                <x-rating-stars rating="5" />
                <p class="mt-4 text-gray-600">
                    "Mi papá recuperó independencia gracias a las adaptaciones del hogar."
                </p>
                <div class="mt-4 font-semibold text-[#83d77c]">
                    — Carlos R.
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-xl hover:-translate-y-3 transition">
                <x-rating-stars rating="4" />
                <p class="mt-4 text-gray-600">
                    "Profesionalismo y tecnología en un solo lugar."
                </p>
                <div class="mt-4 font-semibold text-[#E28987]">
                    — Laura M.
                </div>
            </div>

        </div>

    </div>
</section>

<!-- SECCIÓN DE CONFIANZA -->
<section class="py-16 bg-white reveal">

    <div class="container mx-auto px-6 grid md:grid-cols-4 gap-8 text-center">

        <div class="p-6">
            <div class="text-4xl mb-4 text-[#83D77C]">🚚</div>
            <h4 class="font-semibold mb-2">Envíos Rápidos</h4>
            <p class="text-gray-500 text-sm">
                Entregas seguras en todo el país
            </p>
        </div>

        <div class="p-6">
            <div class="text-4xl mb-4 text-[#E48F62]">🔒</div>
            <h4 class="font-semibold mb-2">Pago Seguro</h4>
            <p class="text-gray-500 text-sm">
                Transacciones 100% protegidas
            </p>
        </div>

        <div class="p-6">
            <div class="text-4xl mb-4 text-[#FFBB51]">↩️</div>
            <h4 class="font-semibold mb-2">Devoluciones Fáciles</h4>
            <p class="text-gray-500 text-sm">
                Cambios sin complicaciones
            </p>
        </div>

        <div class="p-6">
            <div class="text-4xl mb-4 text-[#E28987]">⭐</div>
            <h4 class="font-semibold mb-2">Calidad Garantizada</h4>
            <p class="text-gray-500 text-sm">
                Productos seleccionados premium
            </p>
        </div>

    </div>

</section>



@endsection
