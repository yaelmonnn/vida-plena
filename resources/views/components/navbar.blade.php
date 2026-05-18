<nav x-data="{ open: false }"
    class="fixed top-0 left-0 w-full z-50 backdrop-blur-xl bg-white/90
            border-b border-gray-200 shadow-[0_6px_20px_rgba(0,0,0,0.12)]">

    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

        <!-- LOGO -->
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/logo.png') }}" class="h-14 md:h-16 w-auto object-contain">

            <span class="hidden sm:block text-2xl md:text-3xl font-bold text-[#E48F62] tracking-wide">
                VIDA <span class="text-[#83d77c]">PLENA</span>
            </span>
        </div>

        <!-- MENU DESKTOP -->
        <div class="hidden md:flex gap-10 font-medium text-gray-700 text-lg">
            @foreach ($secciones as $seccion)
                <a href="{{ route($seccion->ruta) }}" class="hover:text-[#E48F62] transition">
                    {{ $seccion->seccion }}
                </a>
            @endforeach
        </div>

        <!-- DERECHA -->
        <div class="flex items-center gap-4">

            @auth('usuario')
                {{-- ── Deseos ── --}}
                @php
                    $deseosCount = \App\Models\Deseo::where('usuario_id', Auth::guard('usuario')->id())->count();
                @endphp
                <a href="{{ route('deseos') }}"
                    class="relative text-xl text-gray-700 hover:text-[#E28987] transition cursor-pointer"
                    title="Mis deseos">
                    <i class="fa-regular fa-heart"></i>
                    <span id="deseos-badge"
                        class="absolute -top-2 -right-3 bg-[#E28987] text-white text-xs
                       px-2 py-0.5 rounded-full shadow
                       {{ $deseosCount === 0 ? 'hidden' : '' }}">
                        {{ $deseosCount }}
                    </span>
                </a>
            @endauth

            {{-- ── Carrito ── --}}
            @php
                $carritoCount = Auth::guard('usuario')->check()
                    ? \App\Models\Carrito::where('usuario_id', Auth::guard('usuario')->id())->sum('cantidad')
                    : 0;
            @endphp
            <a href="{{ Auth::guard('usuario')->check() ? route('carrito') : route('login.usuario') }}"
                class="relative text-xl text-gray-700 hover:text-[#E48F62] transition cursor-pointer"
                title="Mi carrito">
                <i class="fa-solid fa-cart-shopping"></i>
                <span id="carrito-badge"
                    class="absolute -top-2 -right-3 bg-[#E28987] text-white text-xs
                   px-2 py-0.5 rounded-full shadow
                   {{ $carritoCount === 0 ? 'hidden' : '' }}">
                    {{ $carritoCount }}
                </span>
            </a>

            @auth('usuario')
                <div class="hidden md:flex items-center gap-4">
                    <div class="flex items-center gap-2 text-gray-700 font-medium">
                        <i class="fa-solid fa-circle-user text-[#E48F62] text-lg"></i>
                        <span>Bienvenido, {{ auth('usuario')->user()->nombre }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout.usuario') }}">
                        @csrf
                        <button type="submit" data-logout
                            class="flex items-center gap-2 bg-[#E48F62] text-white px-5 py-2 rounded-xl
                           hover:bg-[#E28987] transition font-medium cursor-pointer">
                            Salir <i class="fa-solid fa-right-from-bracket text-sm"></i>
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login.usuario') }}"
                    class="hidden md:flex items-center gap-2 bg-[#E48F62] text-white px-5 py-2 rounded-xl
                   hover:bg-[#E28987] transition font-medium cursor-pointer">
                    Ingresar <i class="fa-solid fa-right-to-bracket text-sm"></i>
                </a>
            @endauth

            <button @click="open = !open" class="md:hidden focus:outline-none cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-[#E48F62]" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

    </div>

    <!-- MENU MOVIL -->
    <div x-show="open" x-transition class="md:hidden bg-white border-t border-gray-200 shadow-lg">

        <div class="px-6 py-6 space-y-4 text-lg font-medium">

            @foreach ($secciones as $seccion)
                <a href="{{ route($seccion->ruta) }}" class="block hover:text-[#E48F62] transition">
                    {{ $seccion->seccion }}
                </a>
            @endforeach

            @auth('usuario')
                <div class="mt-4 space-y-4">

                    <!-- Bienvenida -->
                    <div class="flex items-center gap-2 text-gray-700 font-medium">
                        <i class="fa-solid fa-circle-user text-[#E48F62] text-lg"></i>
                        <span>
                            Bienvenido, {{ auth('usuario')->user()->nombre }}
                        </span>
                    </div>

                    <!-- Botón salir -->
                    <form method="POST" action="{{ route('logout.usuario') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 bg-[#E48F62] text-white px-5 py-3 rounded-xl hover:bg-[#E28987] transition font-medium">
                            Cerrar sesión
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>

                </div>
            @else
                <a href="{{ route('login.usuario') }}"
                    class="w-full mt-4 bg-[#E48F62] text-white px-5 py-3 rounded-xl hover:bg-[#E28987] transition">
                    Ingresar
                </a>
            @endauth

        </div>
    </div>

</nav>
