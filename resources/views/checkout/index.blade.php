{{-- resources/views/checkout/index.blade.php --}}
@extends('layout.app')

@section('content')

{{-- Stripe.js --}}
<script src="https://js.stripe.com/v3/"></script>

<div class="min-h-screen bg-[#fafaf8] pt-28 pb-20 px-4" id="checkout-wrapper">
<div class="max-w-6xl mx-auto">

    {{-- ══ Encabezado ══ --}}
    <div class="mb-8 flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-4xl font-extrabold text-gray-800 tracking-tight">
                Finalizar <span class="text-[#E48F62]">Pedido</span>
            </h1>
            <p class="text-gray-400 text-sm mt-1">Completa tu compra de forma segura</p>
        </div>
        <a href="{{ route('carrito') }}"
           class="flex items-center gap-2 text-sm text-[#E48F62] hover:underline font-semibold">
            <i class="fa-solid fa-arrow-left text-xs"></i> Volver al carrito
        </a>
    </div>

    {{-- ══ Timer de sesión ══ --}}
    <div id="timer-bar"
         class="mb-8 bg-white rounded-2xl border border-orange-100 shadow-sm px-6 py-4
                flex items-center justify-between gap-4 flex-wrap">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-[#FFF3EE] flex items-center justify-center">
                <i class="fa-solid fa-clock text-[#E48F62] text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Tiempo restante para completar</p>
                <p class="text-sm text-gray-600">Tu carrito se liberará cuando expire el tiempo</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div id="timer-ring" class="relative w-16 h-16">
                <svg class="w-16 h-16 -rotate-90" viewBox="0 0 64 64">
                    <circle cx="32" cy="32" r="27" fill="none" stroke="#FDE8DC" stroke-width="5"/>
                    <circle id="timer-circle" cx="32" cy="32" r="27" fill="none"
                            stroke="#E48F62" stroke-width="5"
                            stroke-dasharray="169.6" stroke-dashoffset="0"
                            style="transition: stroke-dashoffset 1s linear, stroke 0.5s"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span id="timer-text" class="text-sm font-extrabold text-[#E48F62]">5:00</span>
                </div>
            </div>
            <div id="timer-urgency" class="hidden">
                <span class="text-xs font-bold text-red-500 bg-red-50 px-3 py-1 rounded-full animate-pulse">
                    ¡Apresúrate!
                </span>
            </div>
        </div>
    </div>

    {{-- ══ Grid principal ══ --}}
    <div class="grid lg:grid-cols-[1fr_400px] gap-8 items-start">

        {{-- ── Columna izquierda: Datos ── --}}
        <div class="space-y-6">

            {{-- Datos de envío --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-9 h-9 rounded-xl bg-[#FFF3EE] flex items-center justify-center">
                        <i class="fa-solid fa-location-dot text-[#E48F62]"></i>
                    </div>
                    <h2 class="text-lg font-bold text-gray-800">Datos de envío</h2>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2 grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5 block">Nombre</label>
                            <input type="text" id="envio_nombre"
                                   value="{{ Auth::guard('usuario')->user()->nombre }}"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                                          text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#E48F62]/30
                                          focus:border-[#E48F62] transition">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5 block">Apellido</label>
                            <input type="text" id="envio_apellido"
                                   value="{{ Auth::guard('usuario')->user()->apellido }}"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                                          text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#E48F62]/30
                                          focus:border-[#E48F62] transition">
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5 block">Correo electrónico</label>
                        <input type="email" id="envio_email"
                               value="{{ Auth::guard('usuario')->user()->email }}"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                                      text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#E48F62]/30
                                      focus:border-[#E48F62] transition">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5 block">Teléfono</label>
                        <input type="tel" id="envio_telefono"
                               value="{{ Auth::guard('usuario')->user()->telefono ?? '' }}"
                               placeholder="+52 999 000 0000"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                                      text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#E48F62]/30
                                      focus:border-[#E48F62] transition">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5 block">Ciudad</label>
                        <input type="text" id="envio_ciudad"
                               value="{{ Auth::guard('usuario')->user()->ciudad ?? '' }}"
                               placeholder="Mérida"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                                      text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#E48F62]/30
                                      focus:border-[#E48F62] transition">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5 block">Calle y número</label>
                        <input type="text" id="envio_calle"
                               value="{{ Auth::guard('usuario')->user()->calle ?? '' }}"
                               placeholder="Calle 60 #123"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                                      text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#E48F62]/30
                                      focus:border-[#E48F62] transition">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5 block">Colonia</label>
                        <input type="text" id="envio_colonia"
                               value="{{ Auth::guard('usuario')->user()->colonia ?? '' }}"
                               placeholder="Centro"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                                      text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#E48F62]/30
                                      focus:border-[#E48F62] transition">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5 block">Código postal</label>
                        <input type="text" id="envio_cp"
                               value="{{ Auth::guard('usuario')->user()->cp ?? '' }}"
                               placeholder="97000"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                                      text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#E48F62]/30
                                      focus:border-[#E48F62] transition">
                    </div>
                </div>
            </div>

            {{-- Calendario para servicios (aparece sólo si hay servicios en el carrito) --}}
            @php $tieneServicios = $items->where('producto.tipo', 'servicio')->count() > 0; @endphp

            @if($tieneServicios)
            <div class="bg-white rounded-3xl shadow-sm border border-blue-100 p-6 md:p-8" id="calendario-section">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                        <i class="fa-solid fa-calendar-days text-blue-500"></i>
                    </div>
                    <h2 class="text-lg font-bold text-gray-800">Agenda tu servicio</h2>
                </div>
                <p class="text-xs text-gray-400 mb-6">Selecciona la fecha en que deseas recibir tu servicio.</p>

                {{-- Servicios en el carrito --}}
                @foreach($items->where('producto.tipo', 'servicio') as $item)
                <div class="mb-6 last:mb-0">
                    <p class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-blue-500 text-white text-[10px] flex items-center
                                     justify-center font-bold">{{ $loop->iteration }}</span>
                        {{ $item->producto->nombre }}
                    </p>

                    {{-- Mini calendario --}}
                    <div class="bg-gray-50 rounded-2xl p-4" id="cal-{{ $item->producto_id }}">
                        <div class="flex items-center justify-between mb-4">
                            <button onclick="cambiarMes({{ $item->producto_id }}, -1)"
                                    class="w-8 h-8 rounded-lg hover:bg-gray-200 flex items-center justify-center
                                           text-gray-500 transition cursor-pointer">
                                <i class="fa-solid fa-chevron-left text-xs"></i>
                            </button>
                            <span id="cal-title-{{ $item->producto_id }}"
                                  class="text-sm font-bold text-gray-700 capitalize"></span>
                            <button onclick="cambiarMes({{ $item->producto_id }}, 1)"
                                    class="w-8 h-8 rounded-lg hover:bg-gray-200 flex items-center justify-center
                                           text-gray-500 transition cursor-pointer">
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </button>
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-center mb-2">
                            @foreach(['L','M','X','J','V','S','D'] as $d)
                                <span class="text-[10px] font-bold text-gray-400 uppercase">{{ $d }}</span>
                            @endforeach
                        </div>
                        <div id="cal-days-{{ $item->producto_id }}" class="grid grid-cols-7 gap-1 text-center"></div>
                        <div id="cal-selected-{{ $item->producto_id }}"
                             class="mt-3 text-xs text-blue-600 font-semibold text-center hidden">
                            <i class="fa-solid fa-check mr-1"></i>
                            <span></span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Pago con Stripe --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-9 h-9 rounded-xl bg-[#FFF3EE] flex items-center justify-center">
                        <i class="fa-solid fa-credit-card text-[#E48F62]"></i>
                    </div>
                    <h2 class="text-lg font-bold text-gray-800">Datos de pago</h2>
                    <div class="ml-auto flex items-center gap-2">
                        <i class="fa-brands fa-cc-visa text-2xl text-blue-700"></i>
                        <i class="fa-brands fa-cc-mastercard text-2xl text-red-500"></i>
                        <i class="fa-brands fa-cc-amex text-2xl text-blue-400"></i>
                    </div>
                </div>

                {{-- Stripe Element --}}
                <div id="card-element"
                     class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm
                            focus-within:ring-2 focus-within:ring-[#E48F62]/30
                            focus-within:border-[#E48F62] transition">
                </div>
                <div id="card-errors" class="mt-2 text-xs text-red-500 font-medium hidden">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i>
                    <span id="card-errors-text"></span>
                </div>

                <div class="mt-4 flex items-center gap-2 text-xs text-gray-400">
                    <i class="fa-solid fa-lock text-[#E48F62]"></i>
                    Pago 100% seguro. Tus datos están cifrados con SSL/TLS.
                </div>
            </div>

        </div>

        {{-- ── Columna derecha: Resumen ── --}}
        <div class="space-y-4 lg:sticky lg:top-28">

            {{-- Resumen del pedido --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-bag-shopping text-[#E48F62]"></i>
                    Resumen del pedido
                </h3>

                <div class="space-y-3 max-h-64 overflow-y-auto pr-1 custom-scroll">
                    @foreach($items as $item)
                    <div class="flex gap-3 items-center py-2 border-b border-gray-50 last:border-0">
                        @php $img = $item->producto->imagenes[0] ?? null; @endphp
                        <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-50 flex-shrink-0">
                            @if($img)
                                <img src="{{ asset('images/' . $img->ruta) }}"
                                     alt="{{ $item->producto->nombre }}"
                                     class="w-full h-full object-contain">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fa-solid fa-image text-gray-200"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-gray-700 line-clamp-1">{{ $item->producto->nombre }}</p>
                            <p class="text-[10px] text-gray-400">
                                @if($item->producto->tipo === 'servicio')
                                    <span class="text-blue-500 font-bold">Servicio</span>
                                @else
                                    x{{ $item->cantidad }}
                                @endif
                            </p>
                        </div>
                        <span class="text-sm font-extrabold text-[#E48F62] flex-shrink-0">
                            ${{ number_format($item->producto->precio * $item->cantidad, 2) }}
                        </span>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4 pt-4 border-t border-dashed border-gray-100 space-y-2">
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Subtotal</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Envío</span>
                        <span class="text-green-500 font-semibold">Gratis</span>
                    </div>
                    <div class="flex justify-between text-base font-extrabold text-gray-800 pt-2 border-t border-gray-100">
                        <span>Total</span>
                        <span class="text-[#E48F62] text-lg">${{ number_format($total, 2) }} MXN</span>
                    </div>
                </div>
            </div>

            {{-- Resumen destinatario --}}
            <div class="bg-[#FFF8F5] rounded-3xl border border-[#FFE0CE] p-5">
                <p class="text-xs font-bold text-[#E48F62] uppercase tracking-wide mb-3">
                    <i class="fa-solid fa-user-check mr-1"></i> Enviando a
                </p>
                <p id="resumen-nombre" class="font-bold text-gray-800 text-sm">
                    {{ Auth::guard('usuario')->user()->nombre }} {{ Auth::guard('usuario')->user()->apellido }}
                </p>
                <p id="resumen-dir" class="text-xs text-gray-500 mt-0.5">
                    {{ Auth::guard('usuario')->user()->calle ?? '–' }},
                    {{ Auth::guard('usuario')->user()->colonia ?? '' }},
                    {{ Auth::guard('usuario')->user()->ciudad ?? '' }}
                </p>
                <p id="resumen-email" class="text-xs text-gray-400 mt-1">
                    {{ Auth::guard('usuario')->user()->email }}
                </p>
            </div>

            {{-- Botón pagar --}}
            <button id="btn-pagar"
                    onclick="procesarPago()"
                    class="w-full bg-[#E48F62] hover:bg-[#d07a4e] active:scale-[0.98] text-white font-extrabold
                           py-4 rounded-2xl transition-all duration-200 text-base shadow-lg shadow-[#E48F62]/30
                           flex items-center justify-center gap-3 cursor-pointer">
                <i class="fa-solid fa-lock text-sm"></i>
                Pagar ${{ number_format($total, 2) }} MXN
            </button>

            <p class="text-center text-[10px] text-gray-400">
                Al confirmar aceptas nuestros
                <a href="#" class="text-[#E48F62] underline">Términos y condiciones</a>
            </p>
        </div>
    </div>

</div>
</div>

<style>
.custom-scroll::-webkit-scrollbar { width: 4px; }
.custom-scroll::-webkit-scrollbar-track { background: #f9f9f9; border-radius: 9px; }
.custom-scroll::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 9px; }

.cal-day {
    width: 100%; aspect-ratio: 1;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 600;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: all 0.15s;
    color: #374151;
}
.cal-day:hover:not(.cal-past):not(.cal-empty) {
    background: #FFF3EE;
    color: #E48F62;
}
.cal-day.cal-selected {
    background: #E48F62 !important;
    color: white !important;
}
.cal-day.cal-past {
    color: #d1d5db;
    cursor: not-allowed;
}
.cal-day.cal-today {
    border: 1.5px solid #E48F62;
    color: #E48F62;
}
.cal-day.cal-empty { cursor: default; }
</style>

@push('scripts')
<script>
// ══ STRIPE ══
const stripe = Stripe('{{ config('services.stripe.key') }}');
const elements = stripe.elements();
const cardElement = elements.create('card', {
    style: {
        base: {
            fontSize: '14px',
            color: '#374151',
            fontFamily: 'Outfit, sans-serif',
            '::placeholder': { color: '#9ca3af' },
        },
        invalid: { color: '#ef4444' },
    },
    hidePostalCode: true,
});
cardElement.mount('#card-element');
cardElement.on('change', e => {
    const errDiv = document.getElementById('card-errors');
    const errText = document.getElementById('card-errors-text');
    if (e.error) {
        errDiv.classList.remove('hidden');
        errText.textContent = e.error.message;
    } else {
        errDiv.classList.add('hidden');
    }
});

// ══ TIMER 5 minutos ══
const TIEMPO_TOTAL = 5 * 60;
let tiempoRestante = TIEMPO_TOTAL;
let timerInterval = null;
const circumference = 2 * Math.PI * 27; // r=27

function actualizarTimer() {
    const m = Math.floor(tiempoRestante / 60);
    const s = tiempoRestante % 60;
    document.getElementById('timer-text').textContent =
        `${m}:${s.toString().padStart(2, '0')}`;

    const offset = circumference * (1 - tiempoRestante / TIEMPO_TOTAL);
    document.getElementById('timer-circle').style.strokeDashoffset = offset;

    // Color del círculo
    const circle = document.getElementById('timer-circle');
    if (tiempoRestante <= 60) {
        circle.style.stroke = '#ef4444';
        document.getElementById('timer-text').style.color = '#ef4444';
        document.getElementById('timer-urgency').classList.remove('hidden');
    } else if (tiempoRestante <= 120) {
        circle.style.stroke = '#f97316';
        document.getElementById('timer-text').style.color = '#f97316';
    }

    if (tiempoRestante <= 0) {
        clearInterval(timerInterval);
        sesionExpirada();
    }
    tiempoRestante--;
}

function sesionExpirada() {
    Swal.fire({
        title: 'Tiempo agotado',
        html: 'Tu sesión de compra expiró.<br>Serás redirigido al carrito.',
        icon: 'warning',
        confirmButtonText: 'Ir al carrito',
        confirmButtonColor: '#E48F62',
        allowOutsideClick: false,
        allowEscapeKey: false,
        timer: 5000,
        timerProgressBar: true,
    }).then(() => { window.location.href = '{{ route('carrito') }}'; });
}

actualizarTimer();
timerInterval = setInterval(actualizarTimer, 1000);

// ══ CALENDARIOS DE SERVICIOS ══
const calStates = {};

function initCalendario(productoId) {
    const hoy = new Date();
    calStates[productoId] = {
        anio: hoy.getFullYear(),
        mes: hoy.getMonth(),
        seleccionado: null,
    };
    renderCalendario(productoId);
}

function cambiarMes(productoId, delta) {
    const state = calStates[productoId];
    state.mes += delta;
    if (state.mes > 11) { state.mes = 0; state.anio++; }
    if (state.mes < 0)  { state.mes = 11; state.anio--; }
    renderCalendario(productoId);
}

function renderCalendario(productoId) {
    const state = calStates[productoId];
    const hoy = new Date(); hoy.setHours(0,0,0,0);
    const titulo = new Date(state.anio, state.mes, 1)
        .toLocaleDateString('es-MX', { month: 'long', year: 'numeric' });
    document.getElementById(`cal-title-${productoId}`).textContent = titulo;

    const primerDia = new Date(state.anio, state.mes, 1).getDay();
    const offset = (primerDia === 0) ? 6 : primerDia - 1;
    const diasMes = new Date(state.anio, state.mes + 1, 0).getDate();

    const container = document.getElementById(`cal-days-${productoId}`);
    container.innerHTML = '';

    for (let i = 0; i < offset; i++) {
        const empty = document.createElement('div');
        empty.className = 'cal-day cal-empty';
        container.appendChild(empty);
    }
    for (let d = 1; d <= diasMes; d++) {
        const fecha = new Date(state.anio, state.mes, d);
        const btn = document.createElement('div');
        btn.className = 'cal-day';
        btn.textContent = d;

        if (fecha < hoy) {
            btn.classList.add('cal-past');
        } else {
            if (fecha.getTime() === hoy.getTime()) btn.classList.add('cal-today');
            if (state.seleccionado && fecha.toDateString() === state.seleccionado.toDateString()) {
                btn.classList.add('cal-selected');
            }
            btn.onclick = () => seleccionarDia(productoId, fecha, d);
        }
        container.appendChild(btn);
    }
}

function seleccionarDia(productoId, fecha, dia) {
    calStates[productoId].seleccionado = fecha;
    renderCalendario(productoId);

    const formateada = fecha.toLocaleDateString('es-MX', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    });
    const el = document.getElementById(`cal-selected-${productoId}`);
    el.classList.remove('hidden');
    el.querySelector('span').textContent = formateada;
}

// Inicializar calendarios para cada servicio
@foreach($items->where('producto.tipo', 'servicio') as $item)
    initCalendario({{ $item->producto_id }});
@endforeach

// ══ PROCESO DE PAGO ══
// ══ PROCESO DE PAGO ══
async function procesarPago() {
    const nombre   = document.getElementById('envio_nombre').value.trim();
    const apellido = document.getElementById('envio_apellido').value.trim();
    const email    = document.getElementById('envio_email').value.trim();
    const calle    = document.getElementById('envio_calle').value.trim();

    if (!nombre || !apellido || !email || !calle) {
        Swal.fire({
            icon: 'warning',
            title: 'Completa tus datos',
            text: 'Por favor completa todos los campos de envío.',
            confirmButtonColor: '#E48F62',
        });
        return;
    }

    // Validar fechas de servicio
    @if($tieneServicios)
    let fechasFaltantes = [];
    @foreach($items->where('producto.tipo', 'servicio') as $item)
    if (!calStates[{{ $item->producto_id }}]?.seleccionado) {
        fechasFaltantes.push('{{ $item->producto->nombre }}');
    }
    @endforeach
    if (fechasFaltantes.length) {
        Swal.fire({
            icon: 'info',
            title: 'Selecciona fecha de servicio',
            html: `Por favor selecciona una fecha para:<br><b>${fechasFaltantes.join(', ')}</b>`,
            confirmButtonColor: '#E48F62',
        });
        return;
    }
    @endif

    const btn = document.getElementById('btn-pagar');
    btn.disabled = true;
    btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Procesando...`;

    try {
        // 1. Pedir el client_secret al servidor (NO crea pedido en BD)
        const fechasServicio = {};
        @foreach($items->where('producto.tipo', 'servicio') as $item)
        if (calStates[{{ $item->producto_id }}]?.seleccionado) {
            fechasServicio[{{ $item->producto_id }}] =
                calStates[{{ $item->producto_id }}].seleccionado.toISOString().split('T')[0];
        }
        @endforeach

        const resIntent = await fetch('{{ route('checkout.intent') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                envio: {
                    nombre, apellido, email,
                    telefono: document.getElementById('envio_telefono').value,
                    calle:    document.getElementById('envio_calle').value,
                    colonia:  document.getElementById('envio_colonia').value,
                    ciudad:   document.getElementById('envio_ciudad').value,
                    cp:       document.getElementById('envio_cp').value,
                },
                fechas_servicio: fechasServicio,
            }),
        });

        const intentData = await resIntent.json();
        if (!intentData.ok) throw new Error(intentData.message || 'Error al iniciar el pago.');

        // 2. Stripe cobra la tarjeta con el client_secret
        const { error, paymentIntent } = await stripe.confirmCardPayment(intentData.client_secret, {
            payment_method: {
                card: cardElement,
                billing_details: { name: `${nombre} ${apellido}`, email },
            },
        });

        // Si Stripe rechaza la tarjeta, el error llega aquí — nada se guardó en BD
        if (error) throw new Error(error.message);

        // 3. Pago aprobado por Stripe — ahora sí crear el pedido en BD
        const resConfirm = await fetch('{{ route('checkout.confirmar') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                payment_intent_id: paymentIntent.id, // solo esto, sin pedido_id
            }),
        });

        const confirmData = await resConfirm.json();
        if (!confirmData.ok) throw new Error(confirmData.message);

        // 4. Todo bien — redirigir a éxito
        clearInterval(timerInterval);
        window.location.href = `{{ route('checkout.exito') }}?pedido=${confirmData.pedido_id}`;

    } catch (err) {
        btn.disabled = false;
        btn.innerHTML = `<i class="fa-solid fa-lock text-sm"></i> Pagar ${{ number_format($total, 2) }} MXN`;
        Swal.fire({
            icon: 'error',
            title: 'Error en el pago',
            text: err.message || 'No se pudo procesar el pago. Intenta de nuevo.',
            confirmButtonColor: '#E48F62',
        });
    }
}

// Actualizar resumen en tiempo real
['envio_nombre','envio_apellido'].forEach(id => {
    document.getElementById(id)?.addEventListener('input', () => {
        document.getElementById('resumen-nombre').textContent =
            `${document.getElementById('envio_nombre').value} ${document.getElementById('envio_apellido').value}`;
    });
});
['envio_calle','envio_colonia','envio_ciudad'].forEach(id => {
    document.getElementById(id)?.addEventListener('input', () => {
        document.getElementById('resumen-dir').textContent =
            `${document.getElementById('envio_calle').value}, ` +
            `${document.getElementById('envio_colonia').value}, ` +
            `${document.getElementById('envio_ciudad').value}`;
    });
});
document.getElementById('envio_email')?.addEventListener('input', () => {
    document.getElementById('resumen-email').textContent = document.getElementById('envio_email').value;
});
</script>
@endpush

@endsection
