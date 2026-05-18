@extends('layout.admin')

@section('content')
    @vite('resources/css/productosAdmin.css')

    <div class="adm-wrap">

        {{-- Sidebar --}}
        <x-admin.sidebar :modulos="$modulos" />

        <div class="adm-main">

            {{-- Topbar --}}
            <header class="adm-topbar">
                <div style="display:flex; align-items:center; gap:12px;">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <span class="topbar-title">Pedidos</span>
                </div>
                <div class="topbar-right">
                    <span class="topbar-date">
                        <i class="fa-regular fa-calendar"></i>
                        {{ now()->timezone('America/Mexico_City')->locale('es')->isoFormat('D [de] MMMM, YYYY') }}
                    </span>
                </div>
            </header>

            <main class="adm-content">

                @if (session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: @json(session('success')),
                        confirmButtonColor: '#e05c3a',
                    });
                </script>
                @endif

                {{-- Título --}}
                <p class="section-title">Gestión de pedidos</p>

                {{-- Tarjetas resumen --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    @php
                        $totales = [
                            ['label' => 'Total',     'count' => $pedidos->total(),                                          'color' => '#6b7280', 'icon' => 'fa-box'],
                            ['label' => 'Pagados',   'count' => $conteos['pagado']   ?? 0,                                  'color' => '#10b981', 'icon' => 'fa-circle-check'],
                            ['label' => 'Enviados',  'count' => $conteos['enviado']  ?? 0,                                  'color' => '#3b82f6', 'icon' => 'fa-truck'],
                            ['label' => 'Entregados','count' => $conteos['entregado'] ?? 0,                                 'color' => '#e05c3a', 'icon' => 'fa-house-circle-check'],
                        ];
                    @endphp
                    @foreach ($totales as $t)
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-4 py-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background: {{ $t['color'] }}18; color: {{ $t['color'] }}">
                            <i class="fa-solid {{ $t['icon'] }} text-sm"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-gray-800 leading-none">{{ $t['count'] }}</p>
                            <p class="text-xs text-gray-400 font-semibold mt-0.5">{{ $t['label'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Tabla --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-5">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                        <p class="text-sm font-semibold text-gray-700">Listado de pedidos</p>
                        {{-- Filtro rápido por estado --}}
                        <div class="flex gap-2 flex-wrap">
                            @foreach (['todos' => 'Todos', 'pagado' => 'Pagados', 'enviado' => 'Enviados', 'entregado' => 'Entregados', 'cancelado' => 'Cancelados'] as $val => $lbl)
                            <button onclick="filtrarEstado('{{ $val }}')"
                                data-estado="{{ $val }}"
                                class="filtro-btn text-xs font-semibold px-3 py-1.5 rounded-lg border transition
                                    {{ $val === 'todos' ? 'border-gray-800 bg-gray-800 text-white' : 'border-gray-200 text-gray-500 hover:border-gray-400' }}">
                                {{ $lbl }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    <div class="p-4">
                        <table id="tablaPedidos" class="display w-full">
                            <thead>
                                <tr>
                                    <th># Pedido</th>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pedidos as $p)
                                <tr data-estado="{{ $p->estado }}">
                                    <td class="text-sm font-bold text-gray-700">#{{ $p->Id }}</td>
                                    <td>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">{{ $p->nombre_envio }}</p>
                                            <p class="text-xs text-gray-400">{{ $p->email_envio }}</p>
                                        </div>
                                    </td>
                                    <td class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($p->fr)->timezone('America/Mexico_City')->locale('es')->isoFormat('D MMM YYYY, HH:mm') }}
                                    </td>
                                    <td class="text-sm font-bold" style="color:var(--ink)">
                                        ${{ number_format($p->total, 2) }}
                                    </td>
                                    <td>
                                        @php
                                            $badge = match($p->estado) {
                                                'pagado'    => ['bg-emerald-50',  'text-emerald-700', 'Pagado'],
                                                'enviado'   => ['bg-blue-50',     'text-blue-600',    'Enviado'],
                                                'entregado' => ['bg-orange-50',   'text-orange-600',  'Entregado'],
                                                'cancelado' => ['bg-red-50',      'text-red-500',     'Cancelado'],
                                                default     => ['bg-gray-100',    'text-gray-500',    ucfirst($p->estado)],
                                            };
                                        @endphp
                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $badge[0] }} {{ $badge[1] }}">
                                            {{ $badge[2] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            {{-- Ver detalles --}}
                                            <button
                                                onclick="verDetalle({{ $p->Id }})"
                                                class="text-xs font-bold px-3 py-1.5 rounded-lg border transition hover:opacity-90"
                                                style="border-color:var(--coral); color:var(--coral)">
                                                <i class="fa-solid fa-eye mr-1 text-xs"></i>Ver
                                            </button>
                                            {{-- Marcar como enviado (solo si está pagado) --}}
                                            @if ($p->estado === 'pagado')
                                            <button
                                                onclick="confirmarEnvio({{ $p->Id }}, '{{ addslashes($p->nombre_envio) }}')"
                                                class="text-xs font-bold px-3 py-1.5 rounded-lg border border-blue-200 text-blue-600 hover:bg-blue-50 transition">
                                                <i class="fa-solid fa-truck mr-1 text-xs"></i>Enviar
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Paginación --}}
                <div class="flex justify-end">
                    {{ $pedidos->links() }}
                </div>

            </main>
        </div>
    </div>

    {{-- Overlay mobile --}}
    <div id="overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:99;"
        onclick="closeSidebar()"></div>

    {{-- ══════════════════════════════
         MODAL — Detalle de pedido
    ══════════════════════════════ --}}
    <div id="modal-detalle"
        class="fixed inset-0 z-[200] flex items-center justify-center p-4"
        style="display:none !important;">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="cerrarModal()"></div>

        {{-- Panel --}}
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto z-10">

            {{-- Header --}}
            <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between rounded-t-2xl z-10">
                <div>
                    <p class="text-sm font-black text-gray-800" id="modal-titulo">Detalle del pedido</p>
                    <p class="text-xs text-gray-400" id="modal-subtitulo"></p>
                </div>
                <button onclick="cerrarModal()"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Spinner --}}
            <div id="modal-spinner" class="flex items-center justify-center py-16">
                <i class="fa-solid fa-spinner fa-spin text-2xl" style="color:var(--coral)"></i>
            </div>

            {{-- Contenido dinámico --}}
            <div id="modal-body" class="hidden px-6 py-5 space-y-5">

                {{-- Info cliente + envío --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Cliente</p>
                        <p class="text-sm font-semibold text-gray-800" id="d-nombre"></p>
                        <p class="text-xs text-gray-500" id="d-email"></p>
                        <p class="text-xs text-gray-500 mt-1" id="d-telefono"></p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Dirección de envío</p>
                        <p class="text-sm text-gray-700 leading-relaxed" id="d-direccion"></p>
                    </div>
                </div>

                {{-- Info pedido --}}
                <div class="bg-gray-50 rounded-xl p-4 flex flex-wrap gap-4">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Fecha</p>
                        <p class="text-sm font-semibold text-gray-700 mt-0.5" id="d-fecha"></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Estado</p>
                        <p class="mt-0.5" id="d-estado-badge"></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Pago Stripe</p>
                        <p class="text-xs font-mono text-gray-500 mt-0.5" id="d-stripe"></p>
                    </div>
                </div>

                {{-- Productos --}}
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-3">Productos / Servicios</p>
                    <div id="d-detalles" class="space-y-2"></div>
                </div>

                {{-- Total --}}
                <div class="flex justify-end border-t border-gray-100 pt-4">
                    <div class="text-right">
                        <p class="text-xs text-gray-400 font-semibold">Total pagado</p>
                        <p class="text-2xl font-black" style="color:var(--coral)" id="d-total"></p>
                    </div>
                </div>

                {{-- Botón enviar (si aplica) --}}
                <div id="d-btn-enviar-wrap" class="hidden border-t border-gray-100 pt-4">
                    <button id="d-btn-enviar"
                        class="w-full flex items-center justify-center gap-2 text-white text-sm font-bold px-6 py-3 rounded-xl transition hover:opacity-90"
                        style="background: #3b82f6">
                        <i class="fa-solid fa-truck text-xs"></i>
                        Marcar como enviado y notificar al cliente
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script>
        // ── Sidebar ──
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        document.getElementById('menuToggle').addEventListener('click', () => {
            sidebar.classList.add('open');
            overlay.style.display = 'block';
        });
        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.style.display = 'none';
        }

        // ── DataTable ──
        let table = new DataTable('#tablaPedidos', {
            order: [[2, 'desc']],
            language: {
                decimal:        ',',
                emptyTable:     'No hay pedidos registrados',
                info:           'Mostrando _START_ a _END_ de _TOTAL_ registros',
                infoEmpty:      'Mostrando 0 a 0 de 0 registros',
                infoFiltered:   '(filtrado de _MAX_ registros totales)',
                lengthMenu:     'Mostrar _MENU_ registros',
                loadingRecords: 'Cargando...',
                processing:     'Procesando...',
                search:         'Buscar:',
                zeroRecords:    'No se encontraron resultados',
                paginate:       { first:'Primero', last:'Último', next:'Siguiente', previous:'Anterior' },
            }
        });

        // ── Filtro por estado ──
        function filtrarEstado(estado) {
            // Resaltar botón activo
            document.querySelectorAll('.filtro-btn').forEach(b => {
                const activo = b.dataset.estado === estado;
                b.className = b.className
                    .replace(/border-gray-800 bg-gray-800 text-white|border-gray-200 text-gray-500 hover:border-gray-400/g, '')
                    .trim();
                b.classList.add(...(activo
                    ? ['border-gray-800', 'bg-gray-800', 'text-white']
                    : ['border-gray-200', 'text-gray-500', 'hover:border-gray-400']));
            });

            if (estado === 'todos') {
                table.column(4).search('').draw();
            } else {
                // Busca por el texto del badge
                const textos = { pagado:'Pagado', enviado:'Enviado', entregado:'Entregado', cancelado:'Cancelado' };
                table.column(4).search(textos[estado] ?? estado).draw();
            }
        }

        // ── Modal ──
        const modal      = document.getElementById('modal-detalle');
        const modalSpinner = document.getElementById('modal-spinner');
        const modalBody  = document.getElementById('modal-body');

        function abrirModal() {
            modal.style.setProperty('display', 'flex', 'important');
            document.body.style.overflow = 'hidden';
        }
        function cerrarModal() {
            modal.style.setProperty('display', 'none', 'important');
            document.body.style.overflow = '';
        }

        function verDetalle(id) {
            // Mostrar modal con spinner
            modalSpinner.classList.remove('hidden');
            modalBody.classList.add('hidden');
            abrirModal();

            fetch(`{{ url('admin/pedidos') }}/${id}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(p => renderModal(p))
            .catch(() => {
                cerrarModal();
                Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo cargar el pedido.', confirmButtonColor: '#e05c3a' });
            });
        }

        function renderModal(p) {
            // Cliente
            document.getElementById('modal-titulo').textContent   = `Pedido #${p.Id}`;
            document.getElementById('modal-subtitulo').textContent = `Realizado el ${p.fecha_fmt}`;
            document.getElementById('d-nombre').textContent    = p.nombre_envio;
            document.getElementById('d-email').textContent     = p.email_envio;
            document.getElementById('d-telefono').textContent  = p.telefono_envio ?? '—';
            document.getElementById('d-fecha').textContent     = p.fecha_fmt;
            document.getElementById('d-stripe').textContent    = p.stripe_payment_id ?? '—';

            // Dirección
            const partes = [p.calle_envio, p.colonia_envio, p.ciudad_envio, p.cp_envio].filter(Boolean);
            document.getElementById('d-direccion').textContent = partes.join(', ') || '—';

            // Estado badge
            const badgeMap = {
                pagado:    ['bg-emerald-50',  'text-emerald-700', 'Pagado'],
                enviado:   ['bg-blue-50',     'text-blue-600',    'Enviado'],
                entregado: ['bg-orange-50',   'text-orange-600',  'Entregado'],
                cancelado: ['bg-red-50',      'text-red-500',     'Cancelado'],
            };
            const b = badgeMap[p.estado] ?? ['bg-gray-100', 'text-gray-500', p.estado];
            document.getElementById('d-estado-badge').innerHTML =
                `<span class="text-xs font-semibold px-2.5 py-1 rounded-full ${b[0]} ${b[1]}">${b[2]}</span>`;

            // Total
            document.getElementById('d-total').textContent = `$${parseFloat(p.total).toLocaleString('es-MX', { minimumFractionDigits: 2 })}`;

            // Detalles (productos)
            const cont = document.getElementById('d-detalles');
            cont.innerHTML = '';
            (p.detalles ?? []).forEach(d => {
                const esSvc = d.tipo_producto === 'servicio';
                const fecha = d.fecha_servicio ? `<span class="text-xs text-blue-500 font-semibold ml-2">📅 ${d.fecha_servicio}</span>` : '';
                cont.innerHTML += `
                    <div class="flex items-center gap-3 bg-gray-50 rounded-xl px-4 py-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-xs"
                             style="background:rgba(228,143,98,.12); color:var(--coral)">
                            <i class="fa-solid ${esSvc ? 'fa-spa' : 'fa-box-open'}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">${d.nombre_producto}${fecha}</p>
                            <p class="text-xs text-gray-400">
                                ${d.cantidad} × $${parseFloat(d.precio_unitario).toLocaleString('es-MX', { minimumFractionDigits: 2 })}
                            </p>
                        </div>
                        <p class="text-sm font-bold text-gray-700 whitespace-nowrap">
                            $${parseFloat(d.subtotal).toLocaleString('es-MX', { minimumFractionDigits: 2 })}
                        </p>
                    </div>`;
            });

            // Botón enviar
            const btnWrap = document.getElementById('d-btn-enviar-wrap');
            const btnEnv  = document.getElementById('d-btn-enviar');
            if (p.estado === 'pagado') {
                btnWrap.classList.remove('hidden');
                btnEnv.onclick = () => confirmarEnvio(p.Id, p.nombre_envio, true);
            } else {
                btnWrap.classList.add('hidden');
            }

            // Mostrar contenido
            modalSpinner.classList.add('hidden');
            modalBody.classList.remove('hidden');
        }

        // ── Confirmar envío ──
        function confirmarEnvio(id, nombre, desdeModal = false) {
            Swal.fire({
                title: '¿Marcar como enviado?',
                html: `<span class="text-gray-600 text-sm">Se notificará por correo a <strong>${nombre}</strong> que su pedido está en camino.</span>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="fa-solid fa-truck mr-1"></i> Sí, enviar',
                cancelButtonText:  'Cancelar',
                confirmButtonColor: '#3b82f6',
                cancelButtonColor:  '#6b7280',
                reverseButtons: true,
            }).then(result => {
                if (!result.isConfirmed) return;

                const btn = desdeModal
                    ? document.getElementById('d-btn-enviar')
                    : document.querySelector(`button[onclick*="confirmarEnvio(${id},"]`);

                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Procesando...';
                }

                fetch(`{{ url('admin/pedidos') }}/${id}/enviar`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Pedido enviado!',
                            text: 'Se notificó al cliente por correo.',
                            confirmButtonColor: '#e05c3a',
                            timer: 2200,
                        }).then(() => location.reload());
                    } else {
                        throw new Error(data.message ?? 'Error desconocido');
                    }
                })
                .catch(e => {
                    Swal.fire({ icon: 'error', title: 'Error', text: e.message, confirmButtonColor: '#e05c3a' });
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa-solid fa-truck mr-1"></i> Marcar como enviado y notificar al cliente';
                    }
                });
            });
        }
    </script>
@endsection
