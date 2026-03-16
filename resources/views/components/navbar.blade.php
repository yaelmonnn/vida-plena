<nav x-data="{ open: false }"
     class="fixed top-0 left-0 w-full z-50 backdrop-blur-xl bg-white/90
            border-b border-gray-200 shadow-[0_6px_20px_rgba(0,0,0,0.12)]">

    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

        <!-- LOGO -->
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/logo.png') }}"
                 class="h-14 md:h-16 w-auto object-contain">

            <span class="hidden sm:block text-2xl md:text-3xl font-bold text-[#E48F62] tracking-wide">
                VIDA <span class="text-[#83d77c]">PLENA</span>
            </span>
        </div>

        <!-- MENU DESKTOP -->
        <div class="hidden md:flex gap-10 font-medium text-gray-700 text-lg">
            <a href="{{ route('inicio') }}" class="hover:text-[#E48F62] transition">Inicio</a>
            <a href="{{ route('tienda') }}" class="hover:text-[#E28987] transition">Tienda</a>
            {{-- <a href="#" class="hover:text-[#83d77c] transition">Soluciones</a> --}}
            <a href="#" class="hover:text-[#ffbb51] transition">Contacto</a>
            <a href="#" class="hover:text-[#ffbb51] transition">Nosotros</a>
        </div>

        <!-- DERECHA -->
        <div class="flex items-center gap-6">

            <!-- Carrito -->
            <button class="relative text-xl">
                <i class="fa-solid fa-cart-shopping"></i>
                <span class="absolute -top-2 -right-3 bg-[#E28987] text-white text-xs px-2 py-0.5 rounded-full shadow">
                    0
                </span>
            </button>

            <button class="hidden md:flex items-center gap-2 bg-[#E48F62] text-white px-5 py-2 rounded-xl hover:bg-[#E28987] transition font-medium">
                Ingresar
                <i class="fa-solid fa-right-to-bracket text-sm"></i>
            </button>


            <!-- Botón hamburguesa móvil -->
            <button @click="open = !open"
                    class="md:hidden focus:outline-none">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-7 w-7 text-[#E48F62]"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">

                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>

                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>

            </button>
        </div>

    </div>

    <!-- MENU MOVIL -->
    <div x-show="open"
         x-transition
         class="md:hidden bg-white border-t border-gray-200 shadow-lg">

        <div class="px-6 py-6 space-y-4 text-lg font-medium">

            <a href="{{ route('inicio') }}" class="block hover:text-[#E48F62] transition">Inicio</a>
            <a href="{{ route('tienda') }}" class="block hover:text-[#E28987] transition">Tienda</a>
            {{-- <a href="#" class="block hover:text-[#83d77c] transition">Soluciones</a> --}}
            <a href="#" class="block hover:text-[#ffbb51] transition">Contacto</a>
            <a href="#" class="block hover:text-[#ffbb51] transition">Nosotros</a>

            <button class="w-full mt-4 bg-[#E48F62] text-white px-5 py-3 rounded-xl hover:bg-[#E28987] transition">
                Ingresar
            </button>

        </div>
    </div>

</nav>
