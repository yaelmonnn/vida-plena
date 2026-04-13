<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMINISTRADOR - VIDA PLENA</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.ico') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.ico') }}">

    @vite('resources/css/app.css')

    @vite('resources/js/app.js')

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;900&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">


    @livewireStyles

</head>
<body class="bg-gray-50 text-gray-700">


    <main>
        @yield('content')
    </main>

    <script src="https://kit.fontawesome.com/1e0bbd4af0.js" crossorigin="anonymous"></script>


    @livewireScripts

    @if(session('admin_id'))
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.addEventListener('click', function(e) {
                    const btn = e.target.closest('[data-admin-logout]');
                    if (!btn) return;

                    e.preventDefault();

                    Swal.fire({
                        title: '¿Cerrar sesión?',
                        text: '¿Estás seguro de que quieres salir del panel?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, salir',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#E48F62',
                        cancelButtonColor: '#6b7280',
                        reverseButtons: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            btn.closest('form')?.submit() ??
                                document.querySelector('form[action*="admin/logout"]')?.submit();
                        }
                    });
                });
            </script>
        @endif

</body>
</html>
