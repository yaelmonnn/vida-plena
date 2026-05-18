@extends('layout.app')

@section('content')
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;900&family=Lato:ital,wght@0,300;0,400;0,700;1,300&display=swap"
        rel="stylesheet">

    <div class="min-h-screen bg-[#fafaf8] pt-28 pb-20 px-4">
        <div class="max-w-5xl mx-auto">

            {{-- Encabezado --}}
            <div class="mb-12 reveal">
                <span class="inline-block bg-[#FFF3EC] text-[#E48F62] text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-4">
                    Estamos aquí para ti
                </span>
                <h1 class="text-4xl font-extrabold text-gray-800">
                    Ponte en <span class="text-[#E48F62]">contacto</span>
                </h1>
                <p class="text-gray-400 text-sm mt-2 max-w-md">
                    ¿Tienes alguna pregunta, comentario o solicitud? Con gusto te atendemos.
                </p>
            </div>

            @if (session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: '¡Mensaje enviado!',
                        text: '{{ session('success') }}',
                        confirmButtonColor: '#E48F62',
                        timer: 3000,
                        showConfirmButton: false
                    });
                </script>
            @endif

            @if (session('error'))
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops',
                        text: '{{ session('error') }}',
                        confirmButtonColor: '#E48F62',
                    });
                </script>
            @endif

            <div class="flex flex-col lg:flex-row gap-10 items-start">

                {{-- ── Formulario ── --}}
                <div class="flex-1 min-w-0 reveal">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

                        <h2 class="text-lg font-extrabold text-gray-800 mb-6">Envíanos un mensaje</h2>

                        <form method="POST" action="{{ route('contacto') }}" id="form-contacto" class="space-y-5">
                            @csrf

                            {{-- Nombre --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                                    Nombre completo <span class="text-red-400">*</span>
                                </label>
                                <input type="text" name="nombre" value="{{ old('nombre') }}" required
                                    placeholder="Tu nombre"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800
                                           placeholder-gray-300 focus:outline-none focus:border-[#E48F62] focus:ring-2
                                           focus:ring-[#E48F62]/20 transition @error('nombre') border-red-300 @enderror">
                                @error('nombre')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                                    Correo electrónico <span class="text-red-400">*</span>
                                </label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    placeholder="tu@correo.com"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800
                                           placeholder-gray-300 focus:outline-none focus:border-[#E48F62] focus:ring-2
                                           focus:ring-[#E48F62]/20 transition @error('email') border-red-300 @enderror">
                                @error('email')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Teléfono (opcional) --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                                    Teléfono <span class="text-gray-300 font-normal normal-case">(opcional)</span>
                                </label>
                                <input type="tel" name="telefono" value="{{ old('telefono') }}"
                                    placeholder="+52 999 000 0000"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800
                                           placeholder-gray-300 focus:outline-none focus:border-[#E48F62] focus:ring-2
                                           focus:ring-[#E48F62]/20 transition">
                            </div>

                            {{-- Asunto --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                                    Asunto <span class="text-red-400">*</span>
                                </label>
                                <select name="asunto" required
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800
                                           focus:outline-none focus:border-[#E48F62] focus:ring-2 focus:ring-[#E48F62]/20
                                           transition bg-white @error('asunto') border-red-300 @enderror">
                                    <option value="" disabled {{ old('asunto') ? '' : 'selected' }}>Selecciona un asunto</option>
                                    <option value="pedido"     {{ old('asunto') == 'pedido'     ? 'selected' : '' }}>Consulta sobre pedido</option>
                                    <option value="producto"   {{ old('asunto') == 'producto'   ? 'selected' : '' }}>Información de producto</option>
                                    <option value="servicio"   {{ old('asunto') == 'servicio'   ? 'selected' : '' }}>Agendar servicio</option>
                                    <option value="devolucion" {{ old('asunto') == 'devolucion' ? 'selected' : '' }}>Devolución / cambio</option>
                                    <option value="otro"       {{ old('asunto') == 'otro'       ? 'selected' : '' }}>Otro</option>
                                </select>
                                @error('asunto')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Mensaje --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                                    Mensaje <span class="text-red-400">*</span>
                                </label>
                                <textarea name="mensaje" rows="5" required
                                    placeholder="¿En qué podemos ayudarte?"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800
                                           placeholder-gray-300 focus:outline-none focus:border-[#E48F62] focus:ring-2
                                           focus:ring-[#E48F62]/20 transition resize-none @error('mensaje') border-red-300 @enderror">{{ old('mensaje') }}</textarea>
                                @error('mensaje')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Botón --}}
                            <button type="submit"
                                class="w-full bg-[#E48F62] hover:bg-[#d07a4e] text-white font-bold py-3.5 rounded-2xl
                                       flex items-center justify-center gap-2 transition text-sm">
                                <i class="fa-solid fa-paper-plane text-xs"></i>
                                Enviar mensaje
                            </button>

                        </form>
                    </div>
                </div>

                {{-- ── Info de contacto ── --}}
                <div class="w-full lg:w-80 shrink-0 space-y-5">

                    {{-- Datos --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 reveal">
                        <h3 class="text-base font-extrabold text-gray-800 mb-5">Información de contacto</h3>

                        <div class="space-y-4">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-[#FFF3EC] flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-location-dot text-[#E48F62]"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Dirección</p>
                                    <p class="text-sm text-gray-700 mt-0.5">Calle 60 #495, Centro,<br>Mérida, Yucatán, México</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-[#FFF3EC] flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-phone text-[#E48F62]"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Teléfono</p>
                                    <a href="tel:+529990000000" class="text-sm text-gray-700 hover:text-[#E48F62] transition mt-0.5 block">
                                        +52 999 000 0000
                                    </a>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-[#FFF3EC] flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-envelope text-[#E48F62]"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Email</p>
                                    <a href="mailto:hola@vidaplena.mx" class="text-sm text-gray-700 hover:text-[#E48F62] transition mt-0.5 block">
                                        hola@vidaplena.mx
                                    </a>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-[#FFF3EC] flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-clock text-[#E48F62]"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Horario</p>
                                    <p class="text-sm text-gray-700 mt-0.5">Lun–Vie: 9:00–18:00<br>Sáb: 10:00–14:00</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Redes sociales --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 reveal">
                        <h3 class="text-base font-extrabold text-gray-800 mb-4">Síguenos</h3>
                        <div class="flex gap-3">
                            <a href="#" target="_blank" rel="noopener"
                                class="w-10 h-10 rounded-xl bg-[#FFF3EC] flex items-center justify-center
                                       hover:bg-[#E48F62] group transition">
                                <i class="fa-brands fa-instagram text-[#E48F62] group-hover:text-white transition"></i>
                            </a>
                            <a href="#" target="_blank" rel="noopener"
                                class="w-10 h-10 rounded-xl bg-[#FFF3EC] flex items-center justify-center
                                       hover:bg-[#E48F62] group transition">
                                <i class="fa-brands fa-facebook-f text-[#E48F62] group-hover:text-white transition"></i>
                            </a>
                            <a href="#" target="_blank" rel="noopener"
                                class="w-10 h-10 rounded-xl bg-[#FFF3EC] flex items-center justify-center
                                       hover:bg-[#E48F62] group transition">
                                <i class="fa-brands fa-whatsapp text-[#E48F62] group-hover:text-white transition"></i>
                            </a>
                            <a href="#" target="_blank" rel="noopener"
                                class="w-10 h-10 rounded-xl bg-[#FFF3EC] flex items-center justify-center
                                       hover:bg-[#E48F62] group transition">
                                <i class="fa-brands fa-tiktok text-[#E48F62] group-hover:text-white transition"></i>
                            </a>
                        </div>
                    </div>

                    {{-- WhatsApp CTA --}}
                    <a href="https://wa.me/529995270619?text=Hola%2C%20me%20interesa%20saber%20más%20sobre%20Vida%20Plena"
                        target="_blank" rel="noopener"
                        class="flex items-center gap-3 bg-green-500 hover:bg-green-600 text-white font-bold
                               px-5 py-3.5 rounded-2xl transition w-full justify-center reveal">
                        <i class="fa-brands fa-whatsapp text-xl"></i>
                        Escríbenos por WhatsApp
                    </a>

                </div>
            </div>

            {{-- Mapa (opcional — reemplaza el src con tu embed de Google Maps) --}}
            <div class="mt-12 reveal">
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm" style="height:320px">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.0!2d-89.6237!3d20.9674!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjDCsDU4JzAyLjYiTiA4OcKwMzcnMjUuMyJX!5e0!3m2!1ses!2smx!4v1"
                        width="100%" height="320" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade" title="Ubicación Vida Plena">
                    </iframe>
                </div>
                <p class="text-xs text-gray-300 text-center mt-2">
                    <i class="fa-solid fa-map-pin mr-1"></i>
                    Reemplaza el <code>src</code> del iframe con tu propio embed de Google Maps
                </p>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('form-contacto').addEventListener('submit', function(e) {
                const btn = this.querySelector('[type="submit"]');
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> Enviando…';
            });
        </script>
    @endpush

@endsection
