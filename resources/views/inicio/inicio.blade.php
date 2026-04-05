@extends('layout.app')

@section('content')
@include('partials.tienda-styles')
    <!-- HERO PREMIUM -->
    <x-hero />

    <!-- BENEFICIOS -->
    <section class="py-20 bg-white reveal">
        <div class="max-w-7xl mx-auto px-6 text-center">

            <h2 class="text-3xl font-bold mb-14">
                Un ecosistema diseñado para el bienestar integral
            </h2>

            <div class="grid md:grid-cols-3 gap-10">

                <x-beneficios icono="fa-solid fa-house-circle-check" titulo="Hogar Seguro"
                    descripcion="Adaptaciones inteligentes para prevenir accidentes y mejorar movilidad." color="#83d77c" />

                <x-beneficios icono="fa-solid fa-stethoscope" titulo="Cuidado Profesional"
                    descripcion="Rehabilitación y asistencia especializada coordinada." color="#E28987" />

                <x-beneficios icono="fa-solid fa-person-cane" titulo="Tecnología Asistencial"
                    descripcion="Monitoreo remoto y soluciones inteligentes para tranquilidad familiar." color="#ffbb51" />


            </div>
        </div>
    </section>


    <!-- CATEGORÍAS DESTACADAS -->
    <section class="py-20 bg-linear-to-r from-[#E48F62]/10 to-[#E28987]/10 reveal">
        <div class="max-w-7xl mx-auto px-6">

            <h2 class="text-3xl font-bold mb-12 text-center">
                Explora nuestras soluciones
            </h2>

            <div class="grid md:grid-cols-3 gap-8">

                <x-destacados imagen="hogar-seguro.jpg" titulo="Hogar Seguro y Adaptado" />

                <x-destacados imagen="cuidado-rehabili.jpg" titulo="Cuidado y Rehabilitación" />

                <x-destacados imagen="hogar-tecno.jpg" titulo="Tecnología Asistencial" />


            </div>
        </div>
    </section>

    <!-- PRODUCTOS -->
    {{-- <x-filtros /> --}}
    <livewire:buscar-productos />

    <!-- SECCIÓN CONFIANZA -->
    <x-confianza />

    <!-- CTA FINAL -->
    <section class="py-20 bg-linear-to-r from-[#E28987] to-[#E48F62] text-white text-center reveal">
        <div class="max-w-4xl mx-auto px-6 space-y-6">
            <h2 class="text-4xl font-bold">
                Empieza hoy a construir una VIDA PLENA
            </h2>

            <p class="text-lg opacity-90 font-bold">
                Productos, tecnología y cuidado coordinado en un solo lugar.
            </p>

            <button
                class="bg-[#ffbb51] px-10 py-4 text-white rounded-2xl font-bold
                    hover:scale-105 transition flex items-center gap-3 mx-auto">

                <i class="fa-solid fa-basket-shopping"></i>
                Explorar tienda

            </button>

        </div>
    </section>

    <section class="py-24 bg-linear-to-r from-[#E28987]/10 to-[#E48F62]/10 reveal">
        <div class="max-w-7xl mx-auto px-6 text-center">

            <h2 class="text-3xl font-bold mb-16">
                Lo que dicen nuestras familias
            </h2>

            <div class="grid md:grid-cols-3 gap-10">

                <x-testimonio-card rating="5" texto="Desde que instalamos el sistema de monitoreo, dormimos tranquilos."
                    autor="María G." color="#E48F62" />

                <x-testimonio-card rating="5"
                    texto="Mi papá recuperó independencia gracias a las adaptaciones del hogar." autor="Carlos R."
                    color="#83d77c" />

                <x-testimonio-card rating="4" texto="Profesionalismo y tecnología en un solo lugar." autor="Laura M."
                    color="#E28987" />

            </div>

        </div>
    </section>

    <!-- SECCIÓN DE CONFIANZA -->
    <section class="py-16 bg-white reveal">

        <div class="container mx-auto px-6 grid md:grid-cols-4 gap-8 text-center">

            <x-feature-item icono="fa-solid fa-truck-fast" titulo="Envíos Rápidos"
                descripcion="Entregas seguras en todo el país" color="#83D77C" />

            <x-feature-item icono="fa-solid fa-shield-halved" titulo="Pago Seguro"
                descripcion="Transacciones 100% protegidas" color="#E48F62" />

            <x-feature-item icono="fa-solid fa-rotate-left" titulo="Devoluciones Fáciles"
                descripcion="Cambios sin complicaciones" color="#FFBB51" />

            <x-feature-item icono="fa-solid fa-star" titulo="Calidad Garantizada"
                descripcion="Productos seleccionados premium" color="#E28987" />


        </div>

    </section>
@endsection
