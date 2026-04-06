@extends('layout.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;900&family=Lato:ital,wght@0,300;0,400;0,700;1,300&display=swap" rel="stylesheet">

@vite('resources/css/registroUsuario.css')


<div class="auth-body auth-bg mt-5">
    <div class="auth-blob blob-1"></div>
    <div class="auth-blob blob-2"></div>
    <div class="auth-blob blob-3"></div>

    <div class="auth-card">

        <div class="text-center">
            <a href="{{ route('inicio') }}" class="auth-logo-pill">
                <i class="fa-solid fa-heart-pulse"></i> Vida Plena
            </a>
        </div>

        <h1 class="auth-title text-2xl font-black text-gray-800 text-center leading-tight">
            Crea tu cuenta
        </h1>
        <p class="text-center text-gray-400 text-sm mt-1 mb-6">
            Únete a la comunidad Vida Plena
        </p>

        @if($errors->any())
        <div class="auth-error mb-4">
            <i class="fa-solid fa-circle-exclamation shrink-0"></i>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        <form method="POST" action="{{ route('registro') }}" id="registerForm" class="space-y-4">
            @csrf

            {{-- Nombre + Apellido --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="auth-label" for="nombre">Nombre(s)</label>
                    <div class="auth-input-wrap">
                        <i class="fa-solid fa-user auth-icon"></i>
                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}"
                               placeholder="Ana" class="auth-input {{ $errors->has('nombre') ? 'is-invalid' : '' }}" required>
                    </div>
                    @error('nombre')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="auth-label" for="apellido">Apellido(s)</label>
                    <div class="auth-input-wrap">
                        <i class="fa-solid fa-user auth-icon"></i>
                        <input type="text" id="apellido" name="apellido" value="{{ old('apellido') }}"
                               placeholder="García" class="auth-input {{ $errors->has('apellido') ? 'is-invalid' : '' }}" required>
                    </div>
                    @error('apellido')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Email --}}
            <div>
                <label class="auth-label" for="email">Correo electrónico</label>
                <div class="auth-input-wrap">
                    <i class="fa-solid fa-envelope auth-icon"></i>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           placeholder="tu@correo.mx"
                           class="auth-input {{ $errors->has('email') ? 'is-invalid' : '' }}" required>
                </div>
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Teléfono --}}
            <div>
                <label class="auth-label" for="telefono">Teléfono</label>
                <div class="auth-input-wrap">
                    <i class="fa-solid fa-phone auth-icon"></i>
                    <input type="text" id="telefono" name="telefono" value="{{ old('telefono') }}"
                        placeholder="9991234567"
                        class="auth-input {{ $errors->has('telefono') ? 'is-invalid' : '' }}">
                </div>
            </div>

            {{-- Fecha nacimiento --}}
            <div>
                <label class="auth-label" for="fecha_nacimiento">Fecha de nacimiento</label>
                <div class="auth-input-wrap">
                    <i class="fa-solid fa-calendar auth-icon"></i>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                        class="auth-input" value="{{ old('fecha_nacimiento') }}">
                </div>
            </div>

            {{-- Dirección --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="auth-label">Calle</label>
                    <div class="auth-input-wrap">
                        <i class="fa-solid fa-signs-post auth-icon"></i>
                        <input type="text" name="calle" class="auth-input" value="{{ old('calle') }}">
                    </div>
                </div>
                <div>
                    <label class="auth-label">Colonia</label>
                    <div class="auth-input-wrap">
                        <i class="fa-solid fa-home auth-icon"></i>
                        <input type="text" name="colonia" class="auth-input" value="{{ old('colonia') }}">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="auth-label">Ciudad</label>
                    <div class="auth-input-wrap">
                        <i class="fa-solid fa-city auth-icon"></i>
                        <input type="text" name="ciudad" class="auth-input" value="{{ old('ciudad') }}">
                    </div>
                </div>
                <div>
                    <label class="auth-label">Estado</label>
                    <div class="auth-input-wrap">
                        <i class="fa-solid fa-flag auth-icon"></i>
                        <input type="text" name="estado_dir" class="auth-input" value="{{ old('estado_dir') }}">
                    </div>
                </div>
            </div>

            <div>
                <label class="auth-label">Código Postal</label>
                <div class="auth-input-wrap">
                    <i class="fa-solid fa-mail-bulk auth-icon"></i>
                    <input type="text" name="cp" class="auth-input" value="{{ old('cp') }}">
                </div>
            </div>

            {{-- Password --}}
            <div>
                <label class="auth-label" for="reg_password">Contraseña</label>
                <div class="auth-input-wrap">
                    <i class="fa-solid fa-lock auth-icon"></i>
                    <input type="password" id="password" name="password" placeholder="Mín. 8 caracteres"
                           class="auth-input {{ $errors->has('password') ? 'is-invalid' : '' }}" required>
                    <button type="button" class="toggle-pw" id="togglePw">
                        <i class="fa-solid fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
                <div class="pw-strength-bar">
                    <div class="pw-strength-bar-inner" id="pwStrength"></div>
                </div>
                <p class="text-xs mt-1" id="pwStrengthLabel" style="min-height:1rem;"></p>
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Confirmar password --}}
            <div>
                <label class="auth-label" for="reg_password_confirmation">Confirmar contraseña</label>
                <div class="auth-input-wrap">
                    <i class="fa-solid fa-lock auth-icon"></i>
                    <input type="password" id="reg_password_confirmation" name="password_confirmation"
                           placeholder="Repite tu contraseña"
                           class="auth-input" required>
                </div>
            </div>

            {{-- Términos --}}
            <div class="flex items-start gap-2">
                <input type="checkbox" id="terminos" name="terminos"
                       class="accent-[#E48F62] w-4 h-4 mt-0.5 cursor-pointer shrink-0
                              {{ $errors->has('terminos') ? 'outline outline-1 outline-red-400' : '' }}">
                <label for="terminos" class="text-sm text-gray-500 cursor-pointer leading-relaxed select-none">
                    Acepto los
                    <a href="#" class="text-[#E48F62] font-semibold hover:underline">términos y condiciones</a>
                    y el
                    <a href="#" class="text-[#E48F62] font-semibold hover:underline">aviso de privacidad</a>
                </label>
            </div>
            @error('terminos')<p class="text-red-500 text-xs -mt-2">{{ $message }}</p>@enderror

            <button type="submit" class="auth-btn" id="registerBtn">
                <i class="fa-solid fa-user-plus"></i>
                Crear cuenta gratuita
            </button>

        </form>

        <div class="flex items-center gap-3 my-5 text-gray-300 text-sm">
            <div class="flex-1 h-px bg-gray-200"></div>o<div class="flex-1 h-px bg-gray-200"></div>
        </div>

        <p class="text-center text-sm text-gray-500">
            ¿Ya tienes cuenta?
            <a href="{{ route('login.usuario') }}" class="font-bold text-[#E48F62] hover:underline underline-offset-2">
                Inicia sesión
            </a>
        </p>

    </div>
</div>

@endsection
