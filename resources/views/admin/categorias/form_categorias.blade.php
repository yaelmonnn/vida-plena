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
                    <div class="flex items-center gap-2 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl px-4 py-3 text-sm mb-6">
                        <i class="fa-solid fa-circle-check"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="flex items-center gap-2 bg-red-50 text-red-600 border border-red-200 rounded-xl px-4 py-3 text-sm mb-6">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ session('error') }}
                    </div>
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
                        <i class="fa-solid fa-plus text-xs"></i>
                        Agregar categoría
                    </button>
                </div>

                {{-- PANEL: BUSCAR Y EDITAR --}}
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

                    {{-- Formulario de edición --}}
                    <div id="editForm" class="hidden">
                        <div class="flex items-center justify-between mb-4">
                            <p class="section-title mb-0">Editar categoría</p>
                            <button onclick="closeEdit()"
                                class="text-xs text-gray-400 hover:text-gray-600 flex items-center gap-1">
                                <i class="fa-solid fa-xmark"></i> Cerrar
                            </button>
                        </div>

                        <form id="formEditar" method="POST" action=""
                            class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-6">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre de categoría</label>
                                    <input type="text" id="edit-nombre" name="categoria"
                                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                                        Ícono
                                        <span class="text-gray-400 font-normal text-xs ml-1">(clase Font Awesome, ej: fa-solid fa-spa)</span>
                                    </label>
                                    <div class="flex gap-2 items-center">
                                        <input type="text" id="edit-icono" name="icono"
                                            placeholder="fa-solid fa-tag"
                                            oninput="previewIconEdit(this.value)"
                                            class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm transition font-mono">
                                        <div id="edit-icono-preview"
                                            class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                                            style="background:rgba(228,143,98,.1); color:var(--coral)">
                                            <i id="edit-icono-preview-i" class="fa-solid fa-tag text-sm"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <label class="flex items-center gap-2 cursor-pointer w-fit">
                                <input type="checkbox" name="estado" id="edit-estado" checked
                                    class="w-4 h-4 rounded border-gray-300 accent-(--coral)">
                                <span class="text-sm text-gray-700 font-medium">Categoría activa</span>
                            </label>

                            <div class="flex items-center gap-3 pt-1">
                                <button type="submit"
                                    class="flex items-center gap-2 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition hover:opacity-90"
                                    style="background:var(--coral)">
                                    <i class="fa-solid fa-floppy-disk"></i>
                                    Guardar cambios
                                </button>
                                <button type="button" id="btnEliminar"
                                    class="text-sm font-semibold text-red-400 hover:text-red-600 px-4 py-2.5 rounded-xl border border-red-100 hover:bg-red-50 transition">
                                    <i class="fa-solid fa-trash-can mr-1 text-xs"></i>
                                    Eliminar
                                </button>
                            </div>

                        </form>

                        {{-- Form oculto para DELETE --}}
                        <form id="formEliminar" method="POST" action="" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>

                    </div>

                </div>

                {{-- PANEL: AGREGAR CATEGORÍA --}}
                <div id="panel-add" class="hidden">
                    <form method="POST" action="{{ route('admin.categorias.store') }}"
                        class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre de categoría</label>
                                <input type="text" name="categoria" placeholder="Ej. Bienestar y salud"
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
                                    <input type="text" name="icono" id="add-icono"
                                        placeholder="fa-solid fa-tag"
                                        value="{{ old('icono') }}"
                                        oninput="previewIconAdd(this.value)"
                                        class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm transition font-mono
                                        @error('icono') border-red-400 @enderror">
                                    <div id="add-icono-preview"
                                        class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                                        style="background:rgba(228,143,98,.1); color:var(--coral)">
                                        <i id="add-icono-preview-i" class="fa-solid fa-tag text-sm"></i>
                                    </div>
                                </div>
                                @error('icono')
                                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <label class="flex items-center gap-2 cursor-pointer w-fit">
                            <input type="checkbox" name="estado" checked
                                class="w-4 h-4 rounded border-gray-300 accent-(--coral)">
                            <span class="text-sm text-gray-700 font-medium">Categoría activa</span>
                        </label>

                        <div class="flex items-center gap-3 pt-1">
                            <button type="submit"
                                class="flex items-center gap-2 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition hover:opacity-90"
                                style="background:var(--coral)">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Guardar categoría
                            </button>
                            <button type="reset"
                                class="text-sm font-semibold text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                                Limpiar
                            </button>
                        </div>

                    </form>
                </div>

            </main>
        </div>
    </div>

    {{-- Overlay mobile --}}
    <div id="overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:99;"
        onclick="closeSidebar()"></div>

    <script>
        let table = new DataTable('#myTable');

        @if ($errors->any())
            switchTab('add');
        @endif

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

        function switchTab(tab) {
            const isAdd = tab === 'add';
            document.getElementById('panel-add').classList.toggle('hidden', !isAdd);
            document.getElementById('panel-search').classList.toggle('hidden', isAdd);
            document.getElementById('tab-add').className = (isAdd ? 'tab-active' : 'tab-inactive') +
                ' flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200';
            document.getElementById('tab-search').className = (!isAdd ? 'tab-active' : 'tab-inactive') +
                ' flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200';
            if (!isAdd) document.getElementById('editForm').classList.add('hidden');
        }

        // Preview de ícono en tiempo real (formulario agregar)
        function previewIconAdd(value) {
            const el = document.getElementById('add-icono-preview-i');
            el.className = (value.trim() || 'fa-solid fa-tag') + ' text-sm';
        }

        // Preview de ícono en tiempo real (formulario editar)
        function previewIconEdit(value) {
            const el = document.getElementById('edit-icono-preview-i');
            el.className = (value.trim() || 'fa-solid fa-tag') + ' text-sm';
        }

        function loadEdit(id) {
            const row = document.querySelector(`tr[data-id="${id}"]`);

            document.getElementById('edit-nombre').value  = row.dataset.nombre;
            document.getElementById('edit-icono').value   = row.dataset.icono ?? '';
            document.getElementById('edit-estado').checked = row.dataset.estado === '1';

            // Actualizar preview del ícono
            previewIconEdit(row.dataset.icono ?? '');

            document.getElementById('formEditar').action  = `{{ url('admin/categorias') }}/${id}`;
            document.getElementById('formEliminar').action = `{{ url('admin/categorias') }}/${id}`;

            document.getElementById('btnEliminar').dataset.id     = id;
            document.getElementById('btnEliminar').dataset.nombre = row.dataset.nombre;

            document.getElementById('editForm').classList.remove('hidden');
            document.getElementById('editForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function closeEdit() {
            document.getElementById('editForm').classList.add('hidden');
        }

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
                if (result.isConfirmed) {
                    document.getElementById('formEliminar').submit();
                }
            });
        });
    </script>
@endsection
