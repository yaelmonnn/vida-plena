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
                    <span class="topbar-title">Categorías</span>
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

                @if (session('error'))
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: @json(session('error')),
                        confirmButtonColor: '#e05c3a',
                    });
                </script>
                @endif

                {{-- Título de sección --}}
                <p class="section-title">Gestión de categorías</p>

                {{-- Tabs toggle --}}
                <div class="inline-flex items-center bg-white border border-gray-200 rounded-xl p-1 mb-6 shadow-sm">
                    <button id="tab-search" onclick="switchTab('search')"
                        class="tab-active flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        Buscar y editar
                    </button>
                    <button id="tab-add" onclick="switchTab('add')"
                        class="tab-inactive flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200">
                        <i id="tab-add-icon" class="fa-solid fa-plus text-xs"></i>
                        <span id="tab-add-label">Agregar categoría</span>
                    </button>
                </div>

                {{-- ── PANEL BUSCAR ── --}}
                <div id="panel-search">
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-5">
                        <div class="divide-y divide-gray-100">
                            <div class="bg-white mb-5">
                                <div class="px-5 py-4 border-b border-gray-100">
                                    <p class="text-sm font-semibold text-gray-700">Resultados</p>
                                </div>
                                <div class="p-4">
                                    <table id="myTable" class="display w-full">
                                        <thead>
                                            <tr>
                                                <th>Folio</th>
                                                <th>Categoría</th>
                                                <th>Ícono</th>
                                                <th>Estado</th>
                                                <th>Fecha de registro</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($categorias as $c)
                                                <tr data-id="{{ $c->Id }}"
                                                    data-nombre="{{ $c->categoria }}"
                                                    data-icono="{{ $c->icono }}"
                                                    data-estado="{{ $c->estado }}">
                                                    <td>{{ $c->Id }}</td>
                                                    <td>
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs flex-shrink-0"
                                                                style="background:rgba(228,143,98,.1); color:var(--coral)">
                                                                <i class="{{ $c->icono ?? 'fa-solid fa-tag' }}"></i>
                                                            </div>
                                                            <span class="text-sm font-semibold text-gray-800">{{ $c->categoria }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="text-sm text-gray-500 font-mono text-xs">{{ $c->icono ?? '—' }}</td>
                                                    <td>
                                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                                            {{ $c->estado ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-500' }}">
                                                            {{ $c->estado ? 'Activa' : 'Inactiva' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-sm text-gray-500">
                                                        {{ \Carbon\Carbon::parse($c->fr)->timezone('America/Mexico_City')->locale('es')->isoFormat('D MMM YYYY') }}
                                                    </td>
                                                    <td>
                                                        <button onclick="loadEdit({{ $c->Id }})"
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
                    </div>
                </div>

                {{-- ── PANEL AGREGAR / EDITAR (mismo form) ── --}}
                <div id="panel-add" class="hidden">

                    {{-- Banner modo edición --}}
                    <div id="edit-banner" class="hidden flex items-center justify-between bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-sm mb-5">
                        <span class="text-amber-700 font-semibold flex items-center gap-2">
                            <i class="fa-solid fa-pen-to-square"></i>
                            Editando: <span id="edit-banner-nombre" class="font-bold"></span>
                        </span>
                        <button type="button" onclick="cancelEdit()"
                            class="text-xs text-amber-500 hover:text-amber-700 flex items-center gap-1 font-semibold">
                            <i class="fa-solid fa-xmark"></i> Cancelar edición
                        </button>
                    </div>

                    {{-- Form unificado --}}
                    <form id="formProducto" method="POST" action="{{ route('admin.categorias.store') }}"
                        class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-6">
                        @csrf
                        <input type="hidden" name="_method" id="form-method" value="POST">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre de categoría</label>
                                <input type="text" name="categoria" id="f-nombre"
                                    placeholder="Ej. Bienestar y salud"
                                    value="{{ old('categoria') }}"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition
                                        @error('categoria') border-red-400 @enderror">
                                @error('categoria')
                                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">
                                    Ícono
                                    <span class="text-gray-400 font-normal text-xs ml-1">(clase Font Awesome, ej: fa-solid fa-spa)</span>
                                </label>
                                <div class="flex gap-2 items-center">
                                    <input type="text" name="icono" id="f-icono"
                                        placeholder="fa-solid fa-tag"
                                        value="{{ old('icono') }}"
                                        oninput="previewIcon(this.value)"
                                        class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm transition font-mono
                                            @error('icono') border-red-400 @enderror">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                                        style="background:rgba(228,143,98,.1); color:var(--coral)">
                                        <i id="f-icono-preview" class="fa-solid fa-tag text-sm"></i>
                                    </div>
                                </div>
                                @error('icono')
                                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <label class="flex items-center gap-2 cursor-pointer w-fit">
                            <input type="checkbox" name="estado" id="f-estado" checked
                                class="w-4 h-4 rounded border-gray-300 accent-(--coral)">
                            <span class="text-sm text-gray-700 font-medium">Categoría activa</span>
                        </label>

                        <div class="flex items-center gap-3 pt-1">
                            <button type="submit" id="btn-submit"
                                class="flex items-center gap-2 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition hover:opacity-90"
                                style="background:var(--coral)">
                                <i class="fa-solid fa-floppy-disk"></i>
                                <span id="btn-submit-label">Guardar categoría</span>
                            </button>

                            {{-- Eliminar (solo modo edición) --}}
                            <button type="button" id="btnEliminar"
                                class="hidden text-sm font-semibold text-red-400 hover:text-red-600 px-4 py-2.5 rounded-xl border border-red-100 hover:bg-red-50 transition">
                                <i class="fa-solid fa-trash-can mr-1 text-xs"></i>
                                Eliminar
                            </button>

                            {{-- Limpiar (solo modo creación) --}}
                            <button type="reset" id="btn-reset"
                                class="text-sm font-semibold text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                                Limpiar
                            </button>
                        </div>

                    </form>

                    {{-- Form oculto para DELETE --}}
                    <form id="formEliminar" method="POST" action="" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>

                </div>

            </main>
        </div>
    </div>

    {{-- Overlay mobile --}}
    <div id="overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:99;"
        onclick="closeSidebar()"></div>

    <script>
        // ── DataTable en español ──
        let table = new DataTable('#myTable', {
            language: {
                decimal:        ',',
                emptyTable:     'No hay datos disponibles',
                info:           'Mostrando _START_ a _END_ de _TOTAL_ registros',
                infoEmpty:      'Mostrando 0 a 0 de 0 registros',
                infoFiltered:   '(filtrado de _MAX_ registros totales)',
                lengthMenu:     'Mostrar _MENU_ registros',
                loadingRecords: 'Cargando...',
                processing:     'Procesando...',
                search:         'Buscar:',
                zeroRecords:    'No se encontraron resultados',
                paginate: {
                    first:    'Primero',
                    last:     'Último',
                    next:     'Siguiente',
                    previous: 'Anterior',
                },
                aria: {
                    sortAscending:  ': activar para ordenar ascendente',
                    sortDescending: ': activar para ordenar descendente',
                },
            }
        });

        // ── Si hay errores de validación, abre el tab de agregar ──
        @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error de validación',
                html: `
                    <ul style="text-align:left;">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                `,
                confirmButtonColor: '#e05c3a',
            });
        </script>
        @endif

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
            document.getElementById('tab-add').className = (isAdd ? 'tab-active' : 'tab-inactive') +
                ' flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200';
            document.getElementById('tab-search').className = (!isAdd ? 'tab-active' : 'tab-inactive') +
                ' flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200';

            if (!isAdd && modoEdicion) {
                resetFormModo();
            }
        }

        // ── Preview ícono (form unificado) ──
        function previewIcon(value) {
            document.getElementById('f-icono-preview').className =
                (value.trim() || 'fa-solid fa-tag') + ' text-sm';
        }

        // ── Entrar en modo edición ──
        function loadEdit(id) {
            const row = document.querySelector(`tr[data-id="${id}"]`);

            document.getElementById('f-nombre').value    = row.dataset.nombre;
            document.getElementById('f-icono').value     = row.dataset.icono ?? '';
            document.getElementById('f-estado').checked  = row.dataset.estado === '1';
            previewIcon(row.dataset.icono ?? '');

            document.getElementById('formProducto').action = `{{ url('admin/categorias') }}/${id}`;
            document.getElementById('form-method').value   = 'PUT';

            document.getElementById('formEliminar').action       = `{{ url('admin/categorias') }}/${id}`;
            document.getElementById('btnEliminar').dataset.id    = id;
            document.getElementById('btnEliminar').dataset.nombre = row.dataset.nombre;

            // UI: modo edición
            modoEdicion = true;
            document.getElementById('tab-add-label').textContent   = 'Editar categoría';
            document.getElementById('tab-add-icon').className       = 'fa-solid fa-pen-to-square text-xs';
            document.getElementById('btn-submit-label').textContent = 'Guardar cambios';
            document.getElementById('edit-banner').classList.remove('hidden');
            document.getElementById('edit-banner-nombre').textContent = row.dataset.nombre;
            document.getElementById('btnEliminar').classList.remove('hidden');
            document.getElementById('btn-reset').classList.add('hidden');

            switchTab('add');
        }

        // ── Cancelar edición ──
        function cancelEdit() {
            resetFormModo();
            switchTab('search');
        }

        function resetFormModo() {
            modoEdicion = false;
            const form = document.getElementById('formProducto');
            form.reset();
            form.action                    = `{{ route('admin.categorias.store') }}`;
            document.getElementById('form-method').value  = 'POST';
            document.getElementById('tab-add-label').textContent   = 'Agregar categoría';
            document.getElementById('tab-add-icon').className       = 'fa-solid fa-plus text-xs';
            document.getElementById('btn-submit-label').textContent = 'Guardar categoría';
            document.getElementById('edit-banner').classList.add('hidden');
            document.getElementById('btnEliminar').classList.add('hidden');
            document.getElementById('btn-reset').classList.remove('hidden');
            previewIcon('');
        }

        // ── Eliminar categoría ──
        document.getElementById('btnEliminar').addEventListener('click', function () {
            const nombre = this.dataset.nombre;
            Swal.fire({
                title: '¿Eliminar categoría?',
                html: `<span class="text-gray-600 text-sm">Estás a punto de eliminar <strong>${nombre}</strong>. Esta acción no se puede deshacer.</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#e05c3a',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
            }).then(result => {
                if (result.isConfirmed) document.getElementById('formEliminar').submit();
            });
        });
    </script>
@endsection
