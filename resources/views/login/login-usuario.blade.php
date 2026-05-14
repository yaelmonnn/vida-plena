@extends('layout.app')

@section('content')
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;900&family=Lato:ital,wght@0,300;0,400;0,700;1,300&display=swap"
        rel="stylesheet">


    @vite('resources/css/loginUsuario.css')

    <div class="auth-body auth-bg mt-20">

        {{-- Blobs decorativos --}}
        <div class="auth-blob blob-1"></div>
        <div class="auth-blob blob-2"></div>
        <div class="auth-blob blob-3"></div>

        <div class="auth-card">

            {{-- Logo --}}
            <div class="text-center">
                <a href="{{ route('inicio') }}" class="auth-logo-pill">
                    <i class="fa-solid fa-heart-pulse"></i>
                    Vida Plena
                </a>
            </div>

            {{-- Encabezado --}}
            <h1 class="auth-title text-2xl font-black text-gray-800 text-center leading-tight">
                Bienvenido de vuelta
            </h1>
            <p class="text-center text-gray-400 text-sm mt-1 mb-6">
                Inicia sesión para continuar
            </p>

            {{-- Mensaje de éxito del registro --}}
            {{-- Mensaje de éxito del registro --}}
            @if (session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: '¡Listo!',
                        text: '{{ session('success') }}',
                        confirmButtonColor: '#E48F62'
                    });
                </script>

                {{-- Banner de reenvío --}}
                <div id="reenvioBanner"
                    class="flex items-center justify-between gap-3 bg-orange-50 border border-orange-200 rounded-xl px-4 py-3 mb-4 text-sm">
                    <div class="flex items-center gap-2 text-gray-500">
                        <i class="fa-solid fa-envelope text-[#E48F62]"></i>
                        <span>¿No recibiste el correo?</span>
                    </div>
                    <button onclick="document.getElementById('reenvioModal').classList.remove('hidden')"
                        class="text-[#E48F62] font-semibold hover:underline shrink-0">
                        Reenviar
                    </button>
                </div>
            @endif

            {{-- Modal de reenvío --}}
            {{-- Modal de reenvío --}}
            <div id="reenvioModal"
                class="{{ $errors->reenvio->any() ? '' : 'hidden' }} fixed inset-0 bg-black/40 z-50 flex items-center justify-center">
                <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-sm mx-4">
                    <h2 class="text-lg font-bold text-gray-800 mb-1">Reenviar verificación</h2>
                    <p class="text-sm text-gray-400 mb-4">Ingresa tu correo y te enviaremos un nuevo enlace.</p>
                    <form method="POST" action="{{ route('reenviar.verificacion') }}" id="reenvioForm">
                        @csrf
                        <div class="auth-input-wrap mb-4">
                            <i class="fa-solid fa-envelope auth-icon"></i>
                            <input type="email" name="email" id="reenvioEmail" class="auth-input"
                                placeholder="tu@correo.mx" required>
                        </div>
                        @if ($errors->reenvio->has('email'))
                            {{-- 👈 bag nombrado --}}
                            <p class="text-red-500 text-xs -mt-2 mb-3 flex items-center gap-1">
                                <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                                {{ $errors->reenvio->first('email') }}
                            </p>
                        @endif
                        <div class="flex gap-3">
                            <button type="button" id="btnCancelarReenvio"
                                onclick="document.getElementById('reenvioModal').classList.add('hidden')"
                                class="flex-1 py-2 rounded-xl border border-gray-200 text-gray-500 text-sm hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                Cancelar
                            </button>
                            <button type="submit" id="btnReenviar"
                                class="flex-1 py-2 rounded-xl bg-[#E48F62] text-white text-sm font-semibold hover:bg-[#d07a4e] disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                                <i class="fa-solid fa-paper-plane" id="iconReenviar"></i>
                                <span id="textoReenviar">Reenviar</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if (session('info'))
                <script>
                    Swal.fire({
                        icon: 'info',
                        title: 'Información',
                        text: @json(session('info')),
                        confirmButtonColor: '#E48F62',
                    });
                </script>
            @endif

            {{-- Errores globales --}}
            @if ($errors->any())
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'No se pudo iniciar sesión',
                        text: @json($errors->first()),
                        confirmButtonColor: '#E48F62',
                    });
                </script>
            @endif

            {{-- Formulario --}}
            <form method="POST" action="{{ route('login.usuario') }}" class="space-y-4" id="loginForm">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="auth-label" for="email">Correo electrónico</label>
                    <div class="auth-input-wrap">
                        <i class="fa-solid fa-envelope auth-icon"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            placeholder="tu@correo.mx" autocomplete="email"
                            class="auth-input {{ $errors->has('email') ? 'is-invalid' : '' }}" required>
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="auth-label" for="password" style="margin-bottom:0">Contraseña</label>
                        <a href="#" class="text-[#E48F62] text-xs hover:underline font-semibold">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                    <div class="auth-input-wrap">
                        <i class="fa-solid fa-lock auth-icon"></i>
                        <input type="password" id="password" name="password" placeholder="••••••••"
                            autocomplete="current-password"
                            class="auth-input {{ $errors->has('password') ? 'is-invalid' : '' }}" required>
                        <button type="button" class="toggle-pw" id="togglePw" aria-label="Mostrar contraseña">
                            <i class="fa-solid fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                            <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

                {{-- Submit --}}
                <button type="submit" class="auth-btn" id="submitBtn">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Iniciar sesión
                </button>

            </form>

            {{-- Divider --}}
            <div class="auth-divider my-5">o</div>

            {{-- Registro --}}
            <p class="text-center text-sm text-gray-500">
                ¿No tienes cuenta?
                <a href="{{ route('registro') }}" class="font-bold text-[#E48F62] hover:underline underline-offset-2">
                    Regístrate gratis
                </a>
            </p>



        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('loginForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                grecaptcha.ready(function() {
                    grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {
                            action: 'login'
                        })
                        .then(function(token) {
                            document.getElementById('g-recaptcha-response').value = token;
                            form.submit();
                        });
                });
            });
        </script>


        <script>
            document.getElementById('reenvioForm').addEventListener('submit', function() {
                const btnReenviar = document.getElementById('btnReenviar');
                const btnCancelar = document.getElementById('btnCancelarReenvio');
                const iconReenviar = document.getElementById('iconReenviar');
                const textoReenviar = document.getElementById('textoReenviar');
                const emailInput = document.getElementById('reenvioEmail');

                btnReenviar.disabled = true;
                btnCancelar.disabled = true;

                iconReenviar.className = 'fa-solid fa-circle-notch fa-spin';
                textoReenviar.textContent = 'Reenviando...';
            });
        </script>
    @endpush
@endsection
