<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMINISTRADOR - VIDA PLENA</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.ico') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.ico') }}">



    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-700">


    <main>
        @yield('content')
    </main>

    <x-footer />

    <script src="https://kit.fontawesome.com/1e0bbd4af0.js" crossorigin="anonymous"></script>

    @livewireScripts


</body>
</html>
