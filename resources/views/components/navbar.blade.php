<nav class="relative z-20 px-4 pt-4">
    <div class="shadow-md rounded-2xl" style="background: linear-gradient(to right, #DD8168, #f0a58f);">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <!-- Logo -->
                <div class="flex items-center space-x-2 text-white">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Vida Plena"
                        class="w-16 h-16 object-cover rounded-lg -my-2">
                    <span class="text-xl font-bold text-white">VIDA PLENA</span>
                </div>

                <!-- Menu Items -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#" class="text-white text-lg hover:text-orange-100 font-medium transition duration-300">Inicio</a>
                    <a href="#" class="text-white text-lg hover:text-orange-100 font-medium transition duration-300">Productos</a>
                    <a href="#" class="text-white text-lg hover:text-orange-100 font-medium transition duration-300">Servicios</a>
                    <a href="#" class="text-white text-lg hover:text-orange-100 font-medium transition duration-300">Sobre Nosotros</a>
                </div>


                <!-- Carrito -->
                <div class="flex items-center space-x-2 text-white">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z" />
                    </svg>
                    <span class="font-medium text-lg">Carrito</span>
                </div>

                <!-- Mobile menu button -->
                <button class="md:hidden text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>
