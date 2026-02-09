@extends('layout.app')

@section('title', 'Inicio')

@section('content')
    <!-- Hero Section with Background -->
    <section class="relative bg-linear-to-r from-orange-400 to-orange-300 overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('images/adulto-mayor.jpg') }}" alt="Adulto mayor con cuidadora" class="w-full h-full object-cover">
        </div>

        <!-- Navbar Component -->
        <x-navbar />

        <!-- Hero Content -->
        <div class="relative z-10 container mx-auto px-4 py-16 lg:py-24">
            <div class="max-w-2xl bg-white/90 backdrop-blur-sm rounded-2xl p-8 lg:p-12 shadow-2xl">
                <div class="flex items-center space-x-4 mb-6">
                    <h1 class="text-5xl lg:text-6xl font-bold text-gray-800">VIDA PLENA</h1>
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Vida Plena" class="w-18 h-18 lg:w-20 lg:h-20 object-cover rounded-lg">
                </div>
                <p class="text-xl lg:text-2xl mb-8 leading-relaxed text-gray-700">
                    Tu autonomía es nuestra prioridad, tu tranquilidad nuestra misión. Soluciones integrales para la bienestar del adulto mayor
                </p>
                <button class="bg-teal-400 hover:bg-teal-500 text-white font-semibold px-8 py-4 rounded-lg text-lg transition duration-300 shadow-lg">
                    Explorar Soluciones
                </button>
            </div>
        </div>
    </section>

    <!-- Service Cards Section -->
    <section class="container mx-auto px-4 -mt-16 relative z-10 mb-16">
        <div class="grid md:grid-cols-3 gap-6">
            <x-service-card
                title="Hogar Seguro"
                gradient="linear-gradient(to right, #9BCA93, #b3d6ad)">
                <x-slot name="icon">
                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
                        <circle cx="17" cy="7" r="2" />
                    </svg>
                </x-slot>
            </x-service-card>

            <x-service-card
                title="Cuidado Profesional"
                gradient="linear-gradient(to right, #E7AC50, #f0c078)">
                <x-slot name="icon">
                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                    </svg>
                </x-slot>
            </x-service-card>

            <x-service-card
                title="Tecnología y Monitoreo"
                gradient="linear-gradient(to right, #D37154, #e89b82)">
                <x-slot name="icon">
                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17 1.01L7 1c-1.1 0-2 .9-2 2v18c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V3c0-1.1-.9-1.99-2-1.99zM17 19H7V5h10v14z" />
                        <path d="M8 6h8v2H8zm0 3h8v2H8zm0 3h8v2H8z" />
                    </svg>
                </x-slot>
            </x-service-card>
        </div>
    </section>

    <!-- Kits Destacados Section -->
    <section class="container mx-auto px-4 py-16">
        <h2 class="text-4xl font-bold text-gray-800 mb-12">Kits Destacados</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <x-product-card
                title="Kit Baño Seguro"
                price="150.00"
                image="images/kit-bano-seguro.jpg"
            />
            <x-product-card
                title="Kit de Mobiliad"
                price="190.00"
                image="images/kit-movilidad.jpg"
            />
            <x-product-card
                title="Kit Dormitorio Seguro"
                price="460.00"
                image="images/dormitorio-adulto.jpg"
            />
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="bg-gray-50 py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-800 mb-4">Historias de Confianza</h2>
                <p class="text-xl text-gray-600">Familias que confían en Vida Plena para cuidar a sus seres queridos</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20">
                <x-testimonial-card
                    name="María González"
                    role="Hija de adulto mayor"
                    image="images/usuario.png">
                    "Ahora puedo estar tranquila sabiendo que mi mamá está bien cuidada. Los servicios son profesionales y el equipo es muy humano."
                </x-testimonial-card>

                <x-testimonial-card
                    name="Carlos Mendoza"
                    role="Esposo"
                    image="images/usuario.png">
                    "La tecnología de monitoreo me mantiene conectado con mi familia. Es como tener un cuidador profesional en casa."
                </x-testimonial-card>

                <x-testimonial-card
                    name="Laura Rodríguez"
                    role="Cuidadora"
                    image="images/usuario.png">
                    "El equipamiento adaptado ha mejorado mucho la seguridad del hogar. Mi trabajo es más eficiente ahora."
                </x-testimonial-card>
            </div>

            <!-- Features Section -->
            <div class="text-center mb-12">
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-800 mb-4">¿Por Qué Elegir Vida Plena?</h2>
                <p class="text-xl text-gray-600">Más que productos y servicios, ofrecemos tranquilidad y calidad de vida</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
<x-feature-card
                    title="Autonomía y Dignidad"
                    description="Mantén tu independencia con el apoyo adecuado en cada momento"
                    bgColor="bg-teal-400">
                    <x-slot name="icon">
                        <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                        </svg>
                    </x-slot>
                </x-feature-card>

                <x-feature-card
                    title="Seguridad 24/7"
                    description="Prevención de accidentes con equipamiento profesional adaptado"
                    bgColor="bg-green-400">
                    <x-slot name="icon">
                        <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z" />
                        </svg>
                    </x-slot>
                </x-feature-card>

                <x-feature-card
                    title="Monitoreo Remoto"
                    description="La familia conectada y tranquila con tecnología de punta"
                    bgColor="bg-yellow-400">
                    <x-slot name="icon">
                        <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17 1.01L7 1c-1.1 0-2 .9-2 2v18c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V3c0-1.1-.9-1.99-2-1.99zM17 19H7V5h10v14z" />
                        </svg>
                    </x-slot>
                </x-feature-card>

                <x-feature-card
                    title="Atención Profesional"
                    description="Profesionales especializados en cuidado geriátrico"
                    bgColor="bg-orange-400">
                    <x-slot name="icon">
                        <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.43 12.98c.04-.32.07-.64.07-.98s-.03-.66-.07-.98l2.11-1.65c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.3-.61-.22l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.23-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64l2.11 1.65c-.04.32-.07.65-.07.98s.03.66.07.98l-2.11 1.65c-.19.15-.24.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.59 1.69-.98l2.49 1c.23.09.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.65zM12 15.5c-1.93 0-3.5-1.57-3.5-3.5s1.57-3.5 3.5-3.5 3.5 1.57 3.5 3.5-1.57 3.5-3.5 3.5z" />
                        </svg>
                    </x-slot>
                </x-feature-card>

                <!-- Repetir para los demás features -->
            </div>
        </div>
    </section>
@endsection
