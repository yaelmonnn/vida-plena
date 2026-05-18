@extends('layout.admin')

@section('content')
    @vite('resources/css/productosAdmin.css')

    <div class="adm-wrap">

        <x-admin.sidebar :modulos="$modulos" />

        <div class="adm-main">

            <header class="adm-topbar">

                <div style="display:flex; align-items:center; gap:12px;">

                    <button class="menu-toggle" id="menuToggle">
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <span class="topbar-title">Clientes</span>

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
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: @json(session('success')),
                                confirmButtonColor: '#e05c3a',
                            });
                        });
                    </script>
                @endif

                <p class="section-title">Gestión de clientes</p>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

                    <div class="p-4">

                        <table id="clientesTable" class="display w-full">

                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Correo</th>
                                    <th>Teléfono</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($clientes as $c)
                                    <tr data-id="{{ $c->Id }}" data-nombre="{{ $c->nombre }}"
                                        data-apellido="{{ $c->apellido }}" data-email="{{ $c->email }}"
                                        data-telefono="{{ $c->telefono ?? '—' }}" data-activo="{{ $c->activo ? 1 : 0 }}">
                                        <td>{{ $c->Id }}</td>

                                        <td>
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs"
                                                    style="background:rgba(228,143,98,.1); color:var(--coral)">
                                                    <i class="fa-solid fa-user"></i>
                                                </div>
                                                <span class="text-sm font-semibold">
                                                    {{ $c->nombre }} {{ $c->apellido }}
                                                </span>
                                            </div>
                                        </td>

                                        <td>{{ $c->email }}</td>
                                        <td>{{ $c->telefono ?? '—' }}</td>

                                        <td>
                                            <span
                                                class="text-xs font-semibold px-2.5 py-1 rounded-full
                                        {{ $c->activo ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-500' }}">
                                                {{ $c->activo ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>

                                        <td>
                                            <button onclick="verDetalle({{ $c->Id }})"
                                                class="text-xs font-bold px-3 py-1.5 rounded-lg border transition hover:opacity-90"
                                                style="border-color:var(--coral); color:var(--coral)">
                                                Ver detalle
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

                {{-- Modal detalle cliente --}}
                <div id="modalCliente" class="fixed inset-0 z-50 hidden items-center justify-center"
                    style="background:rgba(0,0,0,.45);">

                    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6 space-y-5">

                        {{-- Header --}}
                        <div class="flex items-center justify-between">

                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                    style="background:rgba(228,143,98,.1); color:var(--coral)">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div>
                                    <p id="modal-nombre" class="text-sm font-bold text-gray-800"></p>
                                    <p id="modal-estado-badge" class="text-xs mt-0.5"></p>
                                </div>
                            </div>

                            <button onclick="cerrarModal()"
                                class="text-gray-400 hover:text-gray-600 transition text-lg leading-none">
                                <i class="fa-solid fa-xmark"></i>
                            </button>

                        </div>

                        {{-- Datos --}}
                        <div class="divide-y divide-gray-100 rounded-xl border border-gray-100 overflow-hidden">

                            <div class="flex items-center gap-3 px-4 py-3 bg-gray-50">
                                <i class="fa-solid fa-envelope text-xs text-gray-400 w-4 text-center"></i>
                                <span class="text-xs text-gray-500">Correo</span>
                                <span id="modal-email" class="text-xs font-semibold text-gray-700 ml-auto"></span>
                            </div>

                            <div class="flex items-center gap-3 px-4 py-3">
                                <i class="fa-solid fa-phone text-xs text-gray-400 w-4 text-center"></i>
                                <span class="text-xs text-gray-500">Teléfono</span>
                                <span id="modal-telefono" class="text-xs font-semibold text-gray-700 ml-auto"></span>
                            </div>

                        </div>

                        {{-- Acciones --}}
                        <div class="flex gap-3 pt-1">

                            <button id="modal-btn-baja" onclick="darDeBaja()"
                                class="flex-1 text-sm font-bold py-2.5 rounded-xl border border-red-200 text-red-500 hover:bg-red-50 transition">
                                <i class="fa-solid fa-user-slash mr-1 text-xs"></i>
                                Dar de baja
                            </button>

                            <button onclick="cerrarModal()"
                                class="flex-1 text-sm font-semibold py-2.5 rounded-xl border border-gray-200 text-gray-500 hover:bg-gray-50 transition">
                                Cerrar
                            </button>

                        </div>

                    </div>

                </div>

                {{-- Form oculto para baja --}}
                <form id="formBaja" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>

            </main>

        </div>

    </div>

    {{-- Overlay sidebar móvil --}}
    <div id="overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:99;"
        onclick="closeSidebar()">
    </div>

    <script>
        new DataTable('#clientesTable', {
            language: {
                decimal: ',',
                emptyTable: 'No hay clientes registrados',
                info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                infoEmpty: 'Mostrando 0 a 0 de 0 registros',
                infoFiltered: '(filtrado de _MAX_ registros totales)',
                lengthMenu: 'Mostrar _MENU_ registros',
                loadingRecords: 'Cargando...',
                processing: 'Procesando...',
                search: 'Buscar:',
                zeroRecords: 'No se encontraron resultados',
                paginate: {
                    first: 'Primero',
                    last: 'Último',
                    next: 'Siguiente',
                    previous: 'Anterior',
                },
            }
        });

        // Sidebar móvil
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

        // Estado actual del modal
        let clienteActualId = null;
        let clienteActualActivo = false;

        function verDetalle(id) {
            const row = document.querySelector(`tr[data-id="${id}"]`);

            clienteActualId = id;
            clienteActualActivo = row.dataset.activo == 1;

            const nombre = `${row.dataset.nombre} ${row.dataset.apellido}`;
            const activo = clienteActualActivo;

            document.getElementById('modal-nombre').textContent = nombre;
            document.getElementById('modal-email').textContent = row.dataset.email;
            document.getElementById('modal-telefono').textContent = row.dataset.telefono;

            const badge = document.getElementById('modal-estado-badge');
            badge.innerHTML = activo ?
                '<span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-semibold">Activo</span>' :
                '<span class="px-2 py-0.5 rounded-full bg-red-50 text-red-500 font-semibold">Inactivo</span>';

            // Ocultar botón de baja si ya está inactivo
            const btnBaja = document.getElementById('modal-btn-baja');
            btnBaja.style.display = activo ? '' : 'none';

            const modal = document.getElementById('modalCliente');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function cerrarModal() {
            const modal = document.getElementById('modalCliente');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            clienteActualId = null;
        }

        // Cerrar modal al hacer click fuera
        document.getElementById('modalCliente').addEventListener('click', function(e) {
            if (e.target === this) cerrarModal();
        });

        function darDeBaja() {
            if (!clienteActualId) return;

            Swal.fire({
                title: '¿Dar de baja al cliente?',
                text: 'El cliente no podrá acceder a su cuenta.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e05c3a',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, dar de baja',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
            }).then(result => {
                if (result.isConfirmed) {
                    const form = document.getElementById('formBaja');
                    form.action = `/admin/clientes/${clienteActualId}`;
                    form.submit();
                }
            });
        }
    </script>
@endsection
