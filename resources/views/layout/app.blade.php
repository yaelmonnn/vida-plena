<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VIDA PLENA</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.ico') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.ico') }}">

    @vite('resources/css/app.css')

    @vite('resources/js/app.js')

    <style>
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s ease;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;900&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">


    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-700">

    <x-navbar />

    <main>
        @yield('content')
    </main>

    <x-footer />

    <script src="https://kit.fontawesome.com/1e0bbd4af0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.userway.org/widget.js" data-account="fZKfHgpiYK"></script>

    @livewireScripts

    <script>
        function revealOnScroll() {
            document.querySelectorAll('.reveal').forEach(el => {
                const elementTop = el.getBoundingClientRect().top;
                if (elementTop < window.innerHeight - 100) {
                    el.classList.add('active');
                }
            });
        }
        revealOnScroll();
        window.addEventListener('scroll', revealOnScroll);

        document.addEventListener('livewire:navigated', revealOnScroll);
        Livewire.hook('commit', ({ succeed }) => {
            succeed(() => {
                requestAnimationFrame(revealOnScroll);
            });
        });
    </script>

    @auth('usuario')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            const INACTIVIDAD_MS = 2 * 60 * 1000;
            const LOGOUT_URL     = '{{ route('logout.usuario') }}';

            let timerInactividad;

            function getCsrf() {
                return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            }

            function cerrarSesionAhora() {
                window.location.href = '{{ route('sesion.expirada') }}';
            }

            function mostrarAviso() {
                Swal.fire({
                    title: 'Sesión cerrada',
                    text: 'Tu sesión fue cerrada por inactividad.',
                    icon: 'info',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#E48F62',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                }).then(() => cerrarSesionAhora());
            }

            function resetInactividad() {
                clearTimeout(timerInactividad);
                timerInactividad = setTimeout(mostrarAviso, INACTIVIDAD_MS);
            }

            ['mousemove', 'keydown', 'click', 'scroll', 'touchstart'].forEach(e =>
                document.addEventListener(e, resetInactividad, { passive: true })
            );

            resetInactividad();
        </script>
    @endauth

</body>
</html>
