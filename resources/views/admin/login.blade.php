{{-- resources/views/admin/login.blade.php --}}
@extends('layout.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;900&family=Lato:ital,wght@0,300;0,400;0,700;1,300&display=swap" rel="stylesheet">

@vite('resources/css/loginUsuario.css')

<div class="auth-body auth-bg">

    <div class="auth-blob blob-1"></div>
    <div class="auth-blob blob-2"></div>
    <div class="auth-blob blob-3"></div>

    <div class="auth-card">

        {{-- Logo --}}
        <div class="text-center">
            <a href="#" class="auth-logo-pill">
                <i class="fa-solid fa-shield-halved"></i>
                Vida Plena Admin
            </a>
        </div>

        {{-- Badge --}}
        <div class="text-center mb-4">
            <span style="
                display: inline-block;
                background: rgba(228,143,98,.12); color: var(--coral);
                font-size: .7rem; font-weight: 700; letter-spacing: .1em;
                text-transform: uppercase; padding: .25rem .75rem;
                border-radius: 9999px; border: 1px solid rgba(228,143,98,.3);
            ">
                <i class="fa-solid fa-lock"></i> &nbsp;Panel restringido
            </span>
        </div>

        {{-- Encabezado --}}
        <h1 class="auth-title text-2xl font-black text-gray-800 text-center leading-tight">
            Acceso administrativo
        </h1>
        <p class="text-center text-gray-400 text-sm mt-1 mb-6">
            Solo personal autorizado
        </p>

        @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: @json(session('success')),
                confirmButtonColor: '#e48f62',
            });
        </script>
        @endif

        @if(session('info'))
        <script>
            Swal.fire({
                icon: 'info',
                title: 'Información',
                text: @json(session('info')),
                confirmButtonColor: '#e48f62',
            });
        </script>
        @endif

        @if($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Acceso denegado',
                text: @json($errors->first()),
                confirmButtonColor: '#e48f62',
            });
        </script>
        @endif

        {{-- Formulario --}}
        <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-4" id="adminForm">
            @csrf

            {{-- Email --}}
            <div>
                <label class="auth-label" for="email">Correo electrónico</label>
                <div class="auth-input-wrap">
                    <i class="fa-solid fa-envelope auth-icon"></i>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="admin@vidaplena.mx"
                        autocomplete="email"
                        class="auth-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        required
                    >
                </div>
            </div>

            {{-- Password --}}
            <div>
                <label class="auth-label" for="password">Contraseña</label>
                <div class="auth-input-wrap">
                    <i class="fa-solid fa-lock auth-icon"></i>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        autocomplete="current-password"
                        class="auth-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        required
                    >
                    <button type="button" class="toggle-pw" id="togglePw" aria-label="Mostrar contraseña">
                        <i class="fa-solid fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

            {{-- Submit --}}
            <button type="submit" class="auth-btn">
                <i class="fa-solid fa-right-to-bracket"></i>
                Ingresar al panel
            </button>

        </form>

    </div>
</div>

<script>
    const btn   = document.getElementById('togglePw');
    const input = document.getElementById('password');
    const icon  = document.getElementById('eyeIcon');
    btn.addEventListener('click', () => {
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        icon.className = show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
    });
</script>

<script>
    document.getElementById('adminForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;

        grecaptcha.ready(function() {
            grecaptcha.execute('{{ config("services.recaptcha.site_key") }}', { action: 'admin_login' })
                .then(function(token) {
                    document.getElementById('g-recaptcha-response').value = token;
                    form.submit();
                });
        });
    });
</script>

@endsection
