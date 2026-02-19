<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIDA PLENA</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.ico') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.ico') }}">

    @vite('resources/css/app.css')
    <script src="//unpkg.com/alpinejs" defer></script>
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

</head>
<body class="bg-gray-50 text-gray-700">

    <x-navbar />

    <main>
        @yield('content')
    </main>

    <x-footer />

</body>

    <script src="https://kit.fontawesome.com/1e0bbd4af0.js" crossorigin="anonymous"></script>
    <script>

        const revealElements = document.querySelectorAll('.reveal');

        const revealOnScroll = () => {
            revealElements.forEach(el => {
                const windowHeight = window.innerHeight;
                const elementTop = el.getBoundingClientRect().top;

                if (elementTop < windowHeight - 100) {
                    el.classList.add('active');
                }
            });
        };

        window.addEventListener('scroll', revealOnScroll);
    </script>


</html>
