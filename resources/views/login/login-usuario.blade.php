@extends('layout.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;900&family=Lato:ital,wght@0,300;0,400;0,700;1,300&display=swap" rel="stylesheet">


@vite('resources/css/loginUsuario.css')

<div class="auth-body auth-bg">

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

        @if(session('success'))
            <div class="auth-success mb-4">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="auth-error" style="background:#fff7ed; border-color:#fdba74; color:#c2410c;">
                <i class="fa-solid fa-circle-info shrink-0"></i>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        {{-- Errores globales --}}
        @if($errors->any())
        <div class="auth-error mb-4">
            <i class="fa-solid fa-circle-exclamation shrink-0"></i>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        {{-- Formulario --}}
        <form method="POST" action="{{ route('login.usuario') }}" class="space-y-4" id="loginForm">
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
                        placeholder="tu@correo.mx"
                        autocomplete="email"
                        class="auth-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        required
                    >
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
                @error('password')
                <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                    {{ $message }}
                </p>
                @enderror
            </div>

            {{-- Recuérdame --}}
            <div class="flex items-center gap-2">
                <input type="checkbox" id="recuerdame" name="recuerdame"
                       class="accent-[#E48F62] w-4 h-4 rounded cursor-pointer">
                <label for="recuerdame" class="text-sm text-gray-500 cursor-pointer select-none">
                    Mantener sesión iniciada
                </label>
            </div>

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
            <a href="{{ route('registro') }}"
               class="font-bold text-[#E48F62] hover:underline underline-offset-2">
                Regístrate gratis
            </a>
        </p>



    </div>
</div>

@endsection
