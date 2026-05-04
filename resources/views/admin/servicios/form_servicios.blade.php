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
                    <span class="topbar-title">Servicios</span>
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
                    <div
                        class="flex items-center gap-2 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl px-4 py-3 text-sm mb-6">
                        <i class="fa-solid fa-circle-check"></i>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Título de sección --}}
                <p class="section-title">Gestión de servicios</p>

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
                        Agregar servicio
                    </button>
                </div>

                <div id="panel-search">

                    {{-- Resultados con DataTable --}}
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
                                                <th>Servicio</th>
                                                <th>Categoría</th>
                                                <th>Precio</th>
                                                <th>Disponibilidad</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($productos as $p)
                                                <tr data-id="{{ $p->Id }}" data-nombre="{{ $p->nombre }}"
                                                    data-categoria="{{ $p->categoria }}"
                                                    data-categoria-id="{{ $p->categoria_id }}"
                                                    data-precio="{{ $p->precio_raw }}"
                                                    data-disponibilidad="{{ $p->disponibilidad }}"
                                                    data-descripcion="{{ $p->descripcion }}"
                                                    data-cantidad="{{ $p->cantidad_disponible }}">
                                                    <td>{{ $p->Id }}</td>
                                                    <td>
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs flex-shrink-0"
                                                                style="background:rgba(228,143,98,.1); color:var(--coral)">
                                                                <i class="fa-solid fa-spa"></i>
                                                            </div>
                                                            <span
                                                                class="text-sm font-semibold text-gray-800">{{ $p->nombre }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="text-sm text-gray-600">{{ $p->categoria }}</td>
                                                    <td class="text-sm font-bold" style="color:var(--ink)">
                                                        {{ $p->precio_fmt }}</td>
                                                    <td>
                                                        <span
                                                            class="text-xs font-semibold px-2.5 py-1 rounded-full
                                                            {{ $p->disponibilidad === 'Disponible' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-500' }}">
                                                            {{ $p->disponibilidad }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button onclick="loadEdit({{ $p->Id }})"
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
                            <p class="section-title mb-0">Editar servicio</p>
                            <button onclick="closeEdit()"
                                class="text-xs text-gray-400 hover:text-gray-600 flex items-center gap-1">
                                <i class="fa-solid fa-xmark"></i> Cerrar
                            </button>
                        </div>

                        <form id="formEditar" method="POST" action=""
                            class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-6"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre</label>
                                    <input type="text" id="edit-nombre" name="nombre"
                                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Categoría</label>
                                    <select name="categoria_id"
                                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm bg-white transition">
                                        @foreach ($categorias as $cat)
                                            <option value="{{ $cat->Id }}">{{ $cat->categoria }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Precio</label>
                                    <div class="relative">
                                        <span
                                            class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-semibold">$</span>
                                        <input type="number" step="0.01" id="edit-precio" name="precio"
                                            class="w-full rounded-lg border border-gray-300 pl-7 pr-3 py-2 text-sm transition">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Cantidad disponible</label>
                                    <input type="number" id="edit-cantidad" name="cantidad_disponible"
                                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition">
                                </div>

                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Descripción</label>
                                <textarea id="edit-descripcion" name="descripcion" rows="3"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm resize-none transition"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Imágenes actuales</label>
                                <div id="edit-imagenes-actuales" class="flex flex-wrap gap-3 mb-3">
                                    <p class="text-xs text-gray-400 italic">Cargando imágenes...</p>
                                </div>

                                <div id="edit-img-upload-wrap">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Subir nuevas imágenes</label>
                                    <div class="flex gap-3 items-center">
                                        <input type="file" id="edit-nuevas-imagenes" multiple accept="image/*"
                                            class="flex-1 text-sm border border-gray-300 rounded-lg p-2 bg-white
                                            file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0
                                            file:text-sm file:font-semibold file:bg-gray-100 file:cursor-pointer
                                            hover:file:bg-gray-200 cursor-pointer">
                                        <button type="button" id="btnSubirImagenes"
                                            class="flex items-center gap-2 text-white text-sm font-bold px-4 py-2 rounded-xl transition hover:opacity-90 whitespace-nowrap"
                                            style="background:var(--coral)">
                                            <i class="fa-solid fa-upload text-xs"></i>
                                            Subir
                                        </button>
                                    </div>
                                    <p id="edit-img-msg" class="text-xs mt-1 hidden"></p>
                                </div>
                            </div>

                            <label class="flex items-center gap-2 cursor-pointer w-fit">
                                <input type="checkbox" name="activo" id="edit-activo" checked
                                    class="w-4 h-4 rounded border-gray-300 accent-(--coral)">
                                <span class="text-sm text-gray-700 font-medium">Servicio activo</span>
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

                {{-- PANEL: AGREGAR SERVICIO --}}
                <div id="panel-add" class="hidden">
                    <form method="POST" action="{{ route('admin.servicios.store') }}"
                        class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-6"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre</label>
                                <input type="text" name="nombre" placeholder="Ej. Instalación de barandal"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition
                                    @error('nombre') border-red-400 @enderror">
                                @error('nombre')
                                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Categoría</label>
                                <select name="categoria_id"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm bg-white transition
                                    @error('categoria_id') border-red-400 @enderror">
                                    <option value="">Selecciona una categoría</option>
                                    @foreach ($categorias as $cat)
                                        <option value="{{ $cat->Id }}"
                                            {{ old('categoria_id') == $cat->Id ? 'selected' : '' }}>
                                            {{ $cat->categoria }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categoria_id')
                                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Precio</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-semibold">$</span>
                                    <input type="number" step="0.01" name="precio" placeholder="0.00"
                                        value="{{ old('precio') }}"
                                        class="w-full rounded-lg border border-gray-300 pl-7 pr-3 py-2 text-sm transition
                                        @error('precio') border-red-400 @enderror">
                                </div>
                                @error('precio')
                                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Cantidad disponible</label>
                                <input type="number" name="cantidad_disponible" placeholder="0"
                                    value="{{ old('cantidad_disponible') }}"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition
                                    @error('cantidad_disponible') border-red-400 @enderror">
                                @error('cantidad_disponible')
                                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Descripción</label>
                            <textarea name="descripcion" rows="3" placeholder="Describe brevemente el servicio..."
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm resize-none transition">{{ old('descripcion') }}</textarea>
                        </div>

                        {{-- Imágenes con preview --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Imágenes <span class="text-gray-400 font-normal text-xs">(máx. 5)</span>
                            </label>
                            <input type="file" name="imagenes[]" multiple accept="image/*" id="fileInput"
                                class="w-full text-sm border border-gray-300 rounded-lg p-2 bg-white
                                file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0
                                file:text-sm file:font-semibold file:bg-gray-100 file:cursor-pointer
                                hover:file:bg-gray-200 cursor-pointer">
                            @error('imagenes')
                                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                            <div id="previewContainer" class="flex flex-wrap gap-3 mt-3"></div>
                        </div>

                        <label class="flex items-center gap-2 cursor-pointer w-fit">
                            <input type="checkbox" name="activo" checked
                                class="w-4 h-4 rounded border-gray-300 accent-(--coral)">
                            <span class="text-sm text-gray-700 font-medium">Servicio activo</span>
                        </label>

                        <div class="flex items-center gap-3 pt-1">
                            <button type="submit"
                                class="flex items-center gap-2 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition hover:opacity-90"
                                style="background:var(--coral)">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Guardar servicio
                            </button>
                            <button type="reset" onclick="document.getElementById('previewContainer').innerHTML=''"
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

        // ── Renderizar miniaturas ──
        function renderImagenes(imgs) {
            const contenedor = document.getElementById('edit-imagenes-actuales');
            contenedor.innerHTML = '';

            if (!imgs.length) {
                contenedor.innerHTML = '<p class="text-xs text-gray-400 italic">Sin imágenes registradas.</p>';
                return;
            }

            document.getElementById('edit-img-upload-wrap').style.display = imgs.length >= 5 ? 'none' : '';

            imgs.forEach(img => {
                const wrap = document.createElement('div');
                wrap.className = 'relative group';

                const el = document.createElement('img');
                el.src = `/images/${img.ruta}`;
                el.alt = img.alt_text ?? '';
                el.className = 'w-20 h-20 object-cover rounded-xl border border-gray-200 shadow-sm';

                const badge = document.createElement('span');
                badge.className = 'absolute top-1 left-1 text-xs font-bold bg-black/50 text-white rounded px-1';
                badge.textContent = img.orden === 0 ? '★' : img.orden;

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className =
                    'absolute -top-2 -right-2 w-5 h-5 rounded-full bg-red-500 text-white text-xs items-center justify-center hidden group-hover:flex';
                btn.innerHTML = '<i class="fa-solid fa-xmark"></i>';
                btn.onclick = () => confirmarEliminarImagen(img.Id, img.ruta);

                wrap.appendChild(el);
                wrap.appendChild(badge);
                wrap.appendChild(btn);
                contenedor.appendChild(wrap);
            });
        }

        // ── Cargar imágenes al abrir editar ──
        function cargarImagenes(id) {
            const contenedor = document.getElementById('edit-imagenes-actuales');
            contenedor.innerHTML = '<p class="text-xs text-gray-400 italic">Cargando imágenes...</p>';

            fetch(`{{ url('admin/servicios') }}/${id}/imagenes`)
                .then(r => r.json())
                .then(imgs => renderImagenes(imgs))
                .catch(() => {
                    contenedor.innerHTML = '<p class="text-xs text-red-400 italic">Error al cargar imágenes.</p>';
                });
        }

        // ── Eliminar imagen con SweetAlert ──
        function confirmarEliminarImagen(imagenId, ruta) {
            Swal.fire({
                title: '¿Eliminar imagen?',
                html: `<img src="/images/${ruta}" class="w-24 h-24 object-cover rounded-xl mx-auto mb-2"><p class="text-sm text-gray-500">Se eliminará permanentemente.</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#e05c3a',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
            }).then(result => {
                if (!result.isConfirmed) return;

                fetch(`{{ url('admin/productos/imagenes') }}/${imagenId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        }
                    })
                    .then(r => r.json())
                    .then(imgs => {
                        renderImagenes(imgs);
                        Swal.fire({
                            icon: 'success',
                            title: 'Imagen eliminada',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    })
                    .catch(() => Swal.fire({
                        icon: 'error',
                        title: 'Error al eliminar'
                    }));
            });
        }

        // ── Subir nuevas imágenes ──
        document.getElementById('btnSubirImagenes').addEventListener('click', function() {
            const input = document.getElementById('edit-nuevas-imagenes');
            const msg = document.getElementById('edit-img-msg');
            const productoId = document.getElementById('btnEliminar').dataset.id;

            if (!input.files.length) {
                msg.textContent = 'Selecciona al menos una imagen.';
                msg.className = 'text-xs mt-1 text-red-400';
                msg.classList.remove('hidden');
                return;
            }

            const formData = new FormData();
            [...input.files].forEach(f => formData.append('imagenes[]', f));
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            this.disabled = true;
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> Subiendo...';

            fetch(`{{ url('admin/servicios') }}/${productoId}/imagenes`, {
                    method: 'POST',
                    body: formData,
                })
                .then(r => r.json())
                .then(data => {
                    if (data.error) {
                        msg.textContent = data.error;
                        msg.className = 'text-xs mt-1 text-red-400';
                        msg.classList.remove('hidden');
                    } else {
                        renderImagenes(data);
                        input.value = '';
                        msg.textContent = 'Imágenes subidas correctamente.';
                        msg.className = 'text-xs mt-1 text-emerald-600';
                        msg.classList.remove('hidden');
                        setTimeout(() => msg.classList.add('hidden'), 3000);
                    }
                })
                .catch(() => {
                    msg.textContent = 'Error al subir las imágenes.';
                    msg.className = 'text-xs mt-1 text-red-400';
                    msg.classList.remove('hidden');
                })
                .finally(() => {
                    this.disabled = false;
                    this.innerHTML = '<i class="fa-solid fa-upload text-xs"></i> Subir';
                });
        });


        function loadEdit(id) {
            const row = document.querySelector(`tr[data-id="${id}"]`);

            document.getElementById('edit-nombre').value = row.dataset.nombre;
            document.getElementById('edit-precio').value = row.dataset.precio;
            document.getElementById('edit-cantidad').value = row.dataset.cantidad;
            document.getElementById('edit-descripcion').value = row.dataset.descripcion;

            document.querySelector('#formEditar select[name="categoria_id"]').value = row.dataset.categoriaId;

            document.getElementById('formEditar').action = `{{ url('admin/servicios') }}/${id}`;
            document.getElementById('formEliminar').action = `{{ url('admin/servicios') }}/${id}`;

            document.getElementById('btnEliminar').dataset.id = id;
            document.getElementById('btnEliminar').dataset.nombre = row.dataset.nombre;

            document.getElementById('editForm').classList.remove('hidden');
            document.getElementById('editForm').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

            cargarImagenes(id);
        }

        function closeEdit() {
            document.getElementById('editForm').classList.add('hidden');
        }

        document.getElementById('btnEliminar').addEventListener('click', function() {
            const nombre = this.dataset.nombre;

            Swal.fire({
                title: '¿Eliminar servicio?',
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

        // ── Preview imágenes ──
        document.getElementById('fileInput')?.addEventListener('change', function() {
            const container = document.getElementById('previewContainer');
            container.innerHTML = '';
            [...this.files].forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    container.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
@endsection
