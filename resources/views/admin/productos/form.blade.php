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
                    <span class="topbar-title">Productos</span>
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

                {{-- Título de sección --}}
                <p class="section-title">Gestión de productos</p>

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
                        <span id="tab-add-label">Agregar producto</span>
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
                                                <th>Producto</th>
                                                <th>Categoría</th>
                                                <th>Precio</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($productos as $p)
                                                <tr data-id="{{ $p->Id }}"
                                                    data-nombre="{{ $p->nombre }}"
                                                    data-categoria="{{ $p->categoria }}"
                                                    data-categoria-id="{{ $p->categoria_id }}"
                                                    data-precio="{{ $p->precio_raw }}"
                                                    data-estado-id="{{ $p->estado_id }}"
                                                    data-disponibilidad="{{ $p->disponibilidad }}"
                                                    data-descripcion="{{ $p->descripcion }}"
                                                    data-cantidad="{{ $p->cantidad_disponible }}">
                                                    <td>{{ $p->Id }}</td>
                                                    <td>
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs flex-shrink-0"
                                                                style="background:rgba(228,143,98,.1); color:var(--coral)">
                                                                <i class="fa-solid {{ $p->tipo === 'servicio' ? 'fa-spa' : 'fa-box-open' }}"></i>
                                                            </div>
                                                            <span class="text-sm font-semibold text-gray-800">{{ $p->nombre }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="text-sm text-gray-600">{{ $p->categoria }}</td>
                                                    <td class="text-sm font-bold" style="color:var(--ink)">{{ $p->precio_fmt }}</td>
                                                    <td>
                                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full
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
                    <form id="formProducto" method="POST" action="{{ route('admin.productos.store') }}"
                        class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-6"
                        enctype="multipart/form-data">
                        @csrf
                        {{-- El campo _method se inyecta dinámicamente al entrar en modo edición --}}
                        <input type="hidden" name="_method" id="form-method" value="POST">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre</label>
                                <input type="text" name="nombre" id="f-nombre"
                                    placeholder="Ej. Barandal de seguridad"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition
                                        @error('nombre') border-red-400 @enderror">
                                @error('nombre')
                                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Categoría</label>
                                <select name="categoria_id" id="f-categoria"
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
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Estado</label>
                                <select name="estado_id" id="f-estado"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm bg-white transition
                                        @error('estado_id') border-red-400 @enderror">
                                    <option value="">Selecciona un estado</option>
                                    @foreach ($estados as $est)
                                        <option value="{{ $est->Id }}"
                                            {{ old('estado_id') == $est->Id ? 'selected' : '' }}>
                                            {{ $est->estado_nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('estado_id')
                                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Precio</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-semibold">$</span>
                                    <input type="number" step="0.01" name="precio" id="f-precio"
                                        placeholder="0.00" value="{{ old('precio') }}"
                                        class="w-full rounded-lg border border-gray-300 pl-7 pr-3 py-2 text-sm transition
                                            @error('precio') border-red-400 @enderror">
                                </div>
                                @error('precio')
                                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Cantidad disponible</label>
                                <input type="number" name="cantidad_disponible" id="f-cantidad"
                                    placeholder="0" value="{{ old('cantidad_disponible') }}"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition
                                        @error('cantidad_disponible') border-red-400 @enderror">
                                @error('cantidad_disponible')
                                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Descripción</label>
                            <textarea name="descripcion" id="f-descripcion" rows="3"
                                placeholder="Describe brevemente el producto o servicio..."
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm resize-none transition">{{ old('descripcion') }}</textarea>
                        </div>

                        {{-- Imágenes --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Imágenes <span class="text-gray-400 font-normal text-xs">(máx. 5)</span>
                            </label>

                            {{-- Imágenes actuales (solo visible en modo edición) --}}
                            <div id="edit-imagenes-wrap" class="hidden mb-3">
                                <p class="text-xs font-semibold text-gray-500 mb-2">Imágenes actuales</p>
                                <div id="edit-imagenes-actuales" class="flex flex-wrap gap-3">
                                    <p class="text-xs text-gray-400 italic">Cargando imágenes...</p>
                                </div>
                            </div>

                            <div id="edit-img-upload-wrap">
                                <input type="file" name="imagenes[]" multiple accept="image/*" id="fileInput"
                                    class="w-full text-sm border border-gray-300 rounded-lg p-2 bg-white
                                        file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0
                                        file:text-sm file:font-semibold file:bg-gray-100 file:cursor-pointer
                                        hover:file:bg-gray-200 cursor-pointer">
                                @error('imagenes')
                                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                                @enderror

                                {{-- Botón subir (solo modo edición) --}}
                                <div id="btn-subir-wrap" class="hidden mt-2">
                                    <div class="flex gap-3 items-center">
                                        <button type="button" id="btnSubirImagenes"
                                            class="flex items-center gap-2 text-white text-sm font-bold px-4 py-2 rounded-xl transition hover:opacity-90 whitespace-nowrap"
                                            style="background:var(--coral)">
                                            <i class="fa-solid fa-upload text-xs"></i>
                                            Subir imágenes
                                        </button>
                                    </div>
                                    <p id="edit-img-msg" class="text-xs mt-1 hidden"></p>
                                </div>

                                <div id="previewContainer" class="flex flex-wrap gap-3 mt-3"></div>
                            </div>
                        </div>

                        <label class="flex items-center gap-2 cursor-pointer w-fit">
                            <input type="checkbox" name="activo" id="f-activo" checked
                                class="w-4 h-4 rounded border-gray-300 accent-(--coral)">
                            <span class="text-sm text-gray-700 font-medium">Producto activo</span>
                        </label>

                        <div class="flex items-center gap-3 pt-1">
                            <button type="submit" id="btn-submit"
                                class="flex items-center gap-2 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition hover:opacity-90"
                                style="background:var(--coral)">
                                <i class="fa-solid fa-floppy-disk"></i>
                                <span id="btn-submit-label">Guardar producto</span>
                            </button>

                            {{-- Eliminar (solo modo edición) --}}
                            <button type="button" id="btnEliminar"
                                class="hidden text-sm font-semibold text-red-400 hover:text-red-600 px-4 py-2.5 rounded-xl border border-red-100 hover:bg-red-50 transition">
                                <i class="fa-solid fa-trash-can mr-1 text-xs"></i>
                                Eliminar
                            </button>

                            {{-- Limpiar (solo modo creación) --}}
                            <button type="reset" id="btn-reset"
                                onclick="document.getElementById('previewContainer').innerHTML=''"
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

            // Si se vuelve a "buscar" y estábamos editando, cancelar edición silenciosamente
            if (!isAdd && modoEdicion) {
                resetFormModo();
            }
        }

        // ── Entrar en modo edición ──
        function loadEdit(id) {
            const row = document.querySelector(`tr[data-id="${id}"]`);

            // Rellenar campos
            document.getElementById('f-nombre').value         = row.dataset.nombre;
            document.getElementById('f-precio').value         = row.dataset.precio;
            document.getElementById('f-cantidad').value       = row.dataset.cantidad;
            document.getElementById('f-descripcion').value    = row.dataset.descripcion;
            document.querySelector('#formProducto select[name="categoria_id"]').value = row.dataset.categoriaId;
            document.getElementById('f-estado').value         = row.dataset.estadoId;

            // Cambiar acción y método del form
            document.getElementById('formProducto').action = `{{ url('admin/productos') }}/${id}`;
            document.getElementById('form-method').value    = 'PUT';

            // Form de eliminar
            document.getElementById('formEliminar').action   = `{{ url('admin/productos') }}/${id}`;
            document.getElementById('btnEliminar').dataset.id     = id;
            document.getElementById('btnEliminar').dataset.nombre = row.dataset.nombre;

            // UI: modo edición
            modoEdicion = true;
            document.getElementById('tab-add-label').textContent = 'Editar producto';
            document.getElementById('tab-add-icon').className     = 'fa-solid fa-pen-to-square text-xs';
            document.getElementById('btn-submit-label').textContent = 'Guardar cambios';
            document.getElementById('edit-banner').classList.remove('hidden');
            document.getElementById('edit-banner-nombre').textContent = row.dataset.nombre;
            document.getElementById('btnEliminar').classList.remove('hidden');
            document.getElementById('btn-reset').classList.add('hidden');
            document.getElementById('edit-imagenes-wrap').classList.remove('hidden');
            document.getElementById('btn-subir-wrap').classList.remove('hidden');

            // Cargar imágenes existentes
            cargarImagenes(id);

            // Cambiar al tab de agregar/editar
            switchTab('add');
        }

        // ── Cancelar edición / volver a modo creación ──
        function cancelEdit() {
            resetFormModo();
            switchTab('search');
        }

        function resetFormModo() {
            modoEdicion = false;
            const form = document.getElementById('formProducto');
            form.reset();
            form.action                   = `{{ route('admin.productos.store') }}`;
            document.getElementById('form-method').value = 'POST';
            document.getElementById('tab-add-label').textContent  = 'Agregar producto';
            document.getElementById('tab-add-icon').className      = 'fa-solid fa-plus text-xs';
            document.getElementById('btn-submit-label').textContent = 'Guardar producto';
            document.getElementById('edit-banner').classList.add('hidden');
            document.getElementById('btnEliminar').classList.add('hidden');
            document.getElementById('btn-reset').classList.remove('hidden');
            document.getElementById('edit-imagenes-wrap').classList.add('hidden');
            document.getElementById('btn-subir-wrap').classList.add('hidden');
            document.getElementById('previewContainer').innerHTML  = '';
            document.getElementById('edit-imagenes-actuales').innerHTML =
                '<p class="text-xs text-gray-400 italic">Cargando imágenes...</p>';
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

        // ── Cargar imágenes por AJAX ──
        function cargarImagenes(id) {
            const contenedor = document.getElementById('edit-imagenes-actuales');
            contenedor.innerHTML = '<p class="text-xs text-gray-400 italic">Cargando imágenes...</p>';

            fetch(`{{ url('admin/productos') }}/${id}/imagenes`)
                .then(r => r.json())
                .then(imgs => renderImagenes(imgs))
                .catch(() => {
                    contenedor.innerHTML = '<p class="text-xs text-red-400 italic">Error al cargar imágenes.</p>';
                });
        }

        // ── Eliminar imagen ──
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
                    Swal.fire({ icon: 'success', title: 'Imagen eliminada', timer: 1500, showConfirmButton: false });
                })
                .catch(() => Swal.fire({ icon: 'error', title: 'Error al eliminar' }));
            });
        }

        // ── Subir nuevas imágenes ──
        document.getElementById('btnSubirImagenes').addEventListener('click', function () {
            const input = document.getElementById('fileInput');
            const msg   = document.getElementById('edit-img-msg');
            const productoId = document.getElementById('btnEliminar').dataset.id;

            if (!input.files.length) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Selecciona imágenes',
                    text: 'Debes seleccionar al menos una imagen.',
                    confirmButtonColor: '#e05c3a',
                });
                return;
            }

            const formData = new FormData();
            [...input.files].forEach(f => formData.append('imagenes[]', f));
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            this.disabled = true;
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> Subiendo...';

            fetch(`{{ url('admin/productos') }}/${productoId}/imagenes`, {
                method: 'POST',
                body: formData,
            })
            .then(r => r.json())
            .then(data => {
                if (data.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error,
                        confirmButtonColor: '#e05c3a',
                    });
                } else {
                    renderImagenes(data);
                    input.value       = '';
                    Swal.fire({
                        icon: 'success',
                        title: 'Imágenes subidas',
                        text: 'Las imágenes se subieron correctamente.',
                        timer: 1800,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al subir las imágenes.',
                    confirmButtonColor: '#e05c3a',
                });
            })
            .finally(() => {
                this.disabled  = false;
                this.innerHTML = '<i class="fa-solid fa-upload text-xs"></i> Subir imágenes';
            });
        });

        // ── Eliminar producto ──
        document.getElementById('btnEliminar').addEventListener('click', function () {
            const nombre = this.dataset.nombre;
            Swal.fire({
                title: '¿Eliminar producto?',
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

        // ── Preview imágenes (modo creación) ──
        document.getElementById('fileInput')?.addEventListener('change', function () {
            if (modoEdicion) return; // en edición no hacer preview, se sube con el botón
            const container = document.getElementById('previewContainer');
            container.innerHTML = '';
            [...this.files].forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-20 h-20 object-cover rounded-xl border border-gray-200';
                    container.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
@endsection
