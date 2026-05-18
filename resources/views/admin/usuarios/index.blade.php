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

                    <span class="topbar-title">Administradores</span>

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

                @if ($errors->any())
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error de validación',
                                html: `<ul style="text-align:left;">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>`,
                                confirmButtonColor: '#e05c3a',
                            });
                            switchTab('add');
                        });
                    </script>
                @endif

                <p class="section-title">Gestión de administradores</p>

                {{-- TABS --}}
                <div class="inline-flex items-center bg-white border border-gray-200 rounded-xl p-1 mb-6 shadow-sm">

                    <button id="tab-search" onclick="switchTab('search')"
                        class="tab-active flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200">
                        <i class="fa-solid fa-users text-xs"></i>
                        Administradores
                    </button>

                    <button id="tab-add" onclick="switchTab('add')"
                        class="tab-inactive flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200">
                        <i id="tab-add-icon" class="fa-solid fa-plus text-xs"></i>
                        <span id="tab-add-label">Agregar administrador</span>
                    </button>

                </div>

                {{-- ── PANEL TABLA ── --}}
                <div id="panel-search">

                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

                        <div class="p-4">

                            <table id="myTable" class="display w-full">

                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Administrador</th>
                                        <th>Correo</th>
                                        <th>Rol</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($usuarios as $u)
                                        <tr data-id="{{ $u->Id }}" data-nombre="{{ $u->nombre }}"
                                            data-apellido="{{ $u->apellido }}" data-email="{{ $u->email }}"
                                            data-rol="{{ $u->rol_id }}" data-activo="{{ $u->activo ? 1 : 0 }}">
                                            <td>{{ $u->Id }}</td>

                                            <td>
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs"
                                                        style="background:rgba(228,143,98,.1); color:var(--coral)">
                                                        <i class="fa-solid fa-user-shield"></i>
                                                    </div>
                                                    <span class="text-sm font-semibold">
                                                        {{ $u->nombre }} {{ $u->apellido }}
                                                    </span>
                                                </div>
                                            </td>

                                            <td>{{ $u->email }}</td>

                                            <td>{{ $u->rol->nombre ?? 'Sin rol' }}</td>

                                            <td>
                                                <span
                                                    class="text-xs font-semibold px-2.5 py-1 rounded-full
                                            {{ $u->activo ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-500' }}">
                                                    {{ $u->activo ? 'Activo' : 'Inactivo' }}
                                                </span>
                                            </td>

                                            <td>
                                                <button onclick="loadEdit({{ $u->Id }})"
                                                    class="text-xs font-bold px-3 py-1.5 rounded-lg border transition hover:opacity-90"
                                                    style="border-color:var(--coral); color:var(--coral)">
                                                    Editar
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

                {{-- ── PANEL AGREGAR / EDITAR ── --}}
                <div id="panel-add" class="hidden">

                    {{-- Banner modo edición --}}
                    <div id="edit-banner"
                        class="hidden flex items-center justify-between bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-sm mb-5">
                        <span class="text-amber-700 font-semibold flex items-center gap-2">
                            <i class="fa-solid fa-pen-to-square"></i>
                            Editando: <span id="edit-banner-nombre" class="font-bold ml-1"></span>
                        </span>
                        <button type="button" onclick="cancelEdit()"
                            class="text-xs text-amber-500 hover:text-amber-700 flex items-center gap-1 font-semibold">
                            <i class="fa-solid fa-xmark"></i> Cancelar edición
                        </button>
                    </div>

                    {{-- Formulario unificado crear / editar --}}
                    <form id="formAdmin" method="POST" action="{{ route('admin.usuarios.store') }}"
                        class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-6">

                        @csrf
                        <input type="hidden" name="_method" id="form-method" value="POST">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre</label>
                                <input type="text" name="nombre" id="f-nombre" placeholder="Ej. Juan"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Apellido</label>
                                <input type="text" name="apellido" id="f-apellido" placeholder="Ej. García"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Correo</label>
                                <input type="email" name="email" id="f-email" placeholder="correo@ejemplo.com"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">
                                    Contraseña
                                    <span id="password-hint" class="hidden text-gray-400 font-normal text-xs ml-1">
                                        (dejar vacío para no cambiar)
                                    </span>
                                </label>
                                <input type="password" name="password" id="f-password" placeholder="••••••••"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Rol</label>
                                <select name="rol_id" id="f-rol"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm bg-white transition">
                                    <option value="">Selecciona un rol</option>
                                    @foreach ($roles as $rol)
                                        <option value="{{ $rol->Id }}">{{ $rol->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <label class="flex items-center gap-2 cursor-pointer w-fit">
                            <input type="checkbox" name="activo" id="f-activo" checked
                                class="w-4 h-4 rounded border-gray-300">
                            <span class="text-sm text-gray-700 font-medium">Administrador activo</span>
                        </label>

                        <div class="flex items-center gap-3 pt-1">

                            <button type="submit" id="btn-submit"
                                class="flex items-center gap-2 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition hover:opacity-90"
                                style="background:var(--coral)">
                                <i class="fa-solid fa-floppy-disk"></i>
                                <span id="btn-submit-label">Guardar administrador</span>
                            </button>

                            {{-- Dar de baja — solo modo edición --}}
                            <button type="button" id="btnEliminar"
                                class="hidden text-sm font-semibold text-red-400 hover:text-red-600 px-4 py-2.5 rounded-xl border border-red-100 hover:bg-red-50 transition">
                                <i class="fa-solid fa-user-slash mr-1 text-xs"></i>
                                Dar de baja
                            </button>

                            {{-- Limpiar — solo modo creación --}}
                            <button type="reset" id="btn-reset"
                                class="text-sm font-semibold text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                                Limpiar
                            </button>

                        </div>

                    </form>

                    {{-- Form oculto para DELETE (baja) --}}
                    <form id="formEliminar" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>

                </div>

            </main>

        </div>

    </div>

    {{-- Overlay sidebar móvil --}}
    <div id="overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:99;"
        onclick="closeSidebar()">
    </div>

    <script>
        // ── DataTable ──
        new DataTable('#myTable', {
            language: {
                decimal: ',',
                emptyTable: 'No hay administradores registrados',
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

        // ── Sidebar móvil ──
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

        // ── Estado del modo ──
        let modoEdicion = false;

        // ── Cambiar tab ──
        function switchTab(tab) {
            const isAdd = tab === 'add';
            document.getElementById('panel-add').classList.toggle('hidden', !isAdd);
            document.getElementById('panel-search').classList.toggle('hidden', isAdd);

            document.getElementById('tab-add').className =
                (isAdd ? 'tab-active' : 'tab-inactive') +
                ' flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200';
            document.getElementById('tab-search').className =
                (!isAdd ? 'tab-active' : 'tab-inactive') +
                ' flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200';

            // Si vuelve a la tabla estando en edición, resetear silenciosamente
            if (!isAdd && modoEdicion) resetFormModo();
        }

        // ── Cargar datos en el form para editar ──
        function loadEdit(id) {
            const row = document.querySelector(`tr[data-id="${id}"]`);

            document.getElementById('f-nombre').value = row.dataset.nombre;
            document.getElementById('f-apellido').value = row.dataset.apellido;
            document.getElementById('f-email').value = row.dataset.email;
            document.getElementById('f-rol').value = row.dataset.rol;
            document.getElementById('f-activo').checked = row.dataset.activo == 1;
            document.getElementById('f-password').value = ''; // siempre en blanco al editar

            // Acción y método
            document.getElementById('formAdmin').action = `/admin/usuarios/${id}`;
            document.getElementById('form-method').value = 'PUT';

            // Form de baja
            document.getElementById('formEliminar').action = `/admin/usuarios/${id}`;
            document.getElementById('btnEliminar').dataset.id = id;
            document.getElementById('btnEliminar').dataset.nombre =
                `${row.dataset.nombre} ${row.dataset.apellido}`;

            // UI modo edición
            modoEdicion = true;
            document.getElementById('tab-add-label').textContent = 'Editar administrador';
            document.getElementById('tab-add-icon').className = 'fa-solid fa-pen-to-square text-xs';
            document.getElementById('btn-submit-label').textContent = 'Guardar cambios';
            document.getElementById('edit-banner').classList.remove('hidden');
            document.getElementById('edit-banner-nombre').textContent =
                `${row.dataset.nombre} ${row.dataset.apellido}`;
            document.getElementById('btnEliminar').classList.remove('hidden');
            document.getElementById('btn-reset').classList.add('hidden');
            document.getElementById('password-hint').classList.remove('hidden');

            switchTab('add');
        }

        // ── Cancelar edición ──
        function cancelEdit() {
            resetFormModo();
            switchTab('search');
        }

        function resetFormModo() {
            modoEdicion = false;
            const form = document.getElementById('formAdmin');
            form.reset();
            form.action = `{{ route('admin.usuarios.store') }}`;
            document.getElementById('form-method').value = 'POST';
            document.getElementById('tab-add-label').textContent = 'Agregar administrador';
            document.getElementById('tab-add-icon').className = 'fa-solid fa-plus text-xs';
            document.getElementById('btn-submit-label').textContent = 'Guardar administrador';
            document.getElementById('edit-banner').classList.add('hidden');
            document.getElementById('btnEliminar').classList.add('hidden');
            document.getElementById('btn-reset').classList.remove('hidden');
            document.getElementById('password-hint').classList.add('hidden');
        }

        // ── Dar de baja ──
        document.getElementById('btnEliminar').addEventListener('click', function() {
            const nombre = this.dataset.nombre;
            Swal.fire({
                title: '¿Dar de baja?',
                html: `<span class="text-gray-600 text-sm">Estás a punto de dar de baja a <strong>${nombre}</strong>.</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e05c3a',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, dar de baja',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
            }).then(result => {
                if (result.isConfirmed) document.getElementById('formEliminar').submit();
            });
        });
    </script>
@endsection
