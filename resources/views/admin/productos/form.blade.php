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

            @if(session('success'))
                <div class="flex items-center gap-2 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl px-4 py-3 text-sm mb-6">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Título de sección --}}
            <p class="section-title">Gestión de productos</p>

            {{-- Tabs toggle --}}
            <div class="inline-flex items-center bg-white border border-gray-200 rounded-xl p-1 mb-6 shadow-sm">
                <button id="tab-add" onclick="switchTab('add')"
                    class="tab-active flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Agregar producto
                </button>
                <button id="tab-search" onclick="switchTab('search')"
                    class="tab-inactive flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    Buscar y editar
                </button>
            </div>

            {{-- ─────────────────────────────────────────
                 PANEL A: AGREGAR PRODUCTO
            ───────────────────────────────────────── --}}
            <div id="panel-add">

                <form class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-6" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- Nombre --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre</label>
                            <input type="text" name="nombre" placeholder="Ej. Crema hidratante"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition">
                        </div>

                        {{-- Categoría --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Categoría</label>
                            <select name="categoria_id"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition bg-white">
                                <option value="">Selecciona una categoría</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->Id }}">{{ $cat->categoria }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tipo --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tipo</label>
                            <select name="tipo"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm bg-white transition">
                                <option value="producto">Producto</option>
                                <option value="servicio">Servicio</option>
                            </select>
                        </div>

                        {{-- Estado --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Estado</label>
                            <select name="estado_id"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm bg-white transition">
                                <option value="">Selecciona un estado</option>
                                @foreach($estados as $est)
                                    <option value="{{ $est->Id }}">{{ $est->estado_nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Precio --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Precio</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-semibold">$</span>
                                <input type="number" step="0.01" name="precio" placeholder="0.00"
                                    class="w-full rounded-lg border border-gray-300 pl-7 pr-3 py-2 text-sm transition">
                            </div>
                        </div>

                        {{-- Cantidad --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Cantidad disponible</label>
                            <input type="number" name="cantidad_disponible" placeholder="0"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition">
                        </div>

                    </div>

                    {{-- Descripción --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Descripción</label>
                        <textarea name="descripcion" rows="3" placeholder="Describe brevemente el producto o servicio..."
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm resize-none transition"></textarea>
                    </div>

                    {{-- Imágenes --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Imágenes</label>
                        <input type="file" name="imagenes[]" multiple accept="image/*" id="fileInput"
                            class="w-full text-sm border border-gray-300 rounded-lg p-2 bg-white
                                   file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0
                                   file:text-sm file:font-semibold file:bg-gray-100 file:cursor-pointer
                                   hover:file:bg-gray-200 cursor-pointer">
                        <div class="img-preview" id="previewContainer"></div>
                    </div>

                    {{-- Activo --}}
                    <label class="flex items-center gap-2 cursor-pointer w-fit">
                        <input type="checkbox" name="activo" checked
                            class="w-4 h-4 rounded border-gray-300 accent-(--coral)">
                        <span class="text-sm text-gray-700 font-medium">Producto activo</span>
                    </label>

                    {{-- Botón --}}
                    <div class="flex items-center gap-3 pt-1">
                        <button type="submit"
                            class="flex items-center gap-2 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition hover:opacity-90"
                            style="background:var(--coral)">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Guardar producto
                        </button>
                        <button type="reset"
                            class="text-sm font-semibold text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                            Limpiar
                        </button>
                    </div>

                </form>

            </div>

            {{-- ─────────────────────────────────────────
                 PANEL B: BUSCAR Y EDITAR
            ───────────────────────────────────────── --}}
            <div id="panel-search" class="hidden">

                {{-- Buscador --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm mb-5">
                    <p class="text-sm font-semibold text-gray-700 mb-3">Buscar producto por nombre o ID</p>
                    <div class="flex gap-3">
                        <div class="relative flex-1">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" id="searchInput" placeholder="Escribe el nombre o ID del producto..."
                                class="w-full rounded-lg border border-gray-300 pl-9 pr-3 py-2 text-sm transition">
                        </div>
                        <button onclick="doSearch()"
                            class="flex items-center gap-2 text-white text-sm font-bold px-5 py-2 rounded-xl transition hover:opacity-90"
                            style="background:var(--coral)">
                            <i class="fa-solid fa-search text-xs"></i>
                            Buscar
                        </button>
                    </div>
                </div>

                {{-- Resultados (estáticos de ejemplo) --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-5">
                    <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100">
                        <p class="text-sm font-semibold text-gray-700">Resultados</p>
                        <span class="text-xs text-gray-400">3 encontrados</span>
                    </div>
                    <div class="divide-y divide-gray-100">

                        {{-- Fila de resultado --}}
                        @php
                        $fakeProducts = [
                            ['id'=>1, 'nombre'=>'Crema hidratante facial', 'categoria'=>'Cuidado facial', 'precio'=>'$320.00', 'estado'=>'Disponible', 'tipo'=>'Producto'],
                            ['id'=>2, 'nombre'=>'Masaje relajante 60min',  'categoria'=>'Spa',           'precio'=>'$850.00', 'estado'=>'Disponible', 'tipo'=>'Servicio'],
                            ['id'=>3, 'nombre'=>'Sérum vitamina C',        'categoria'=>'Cuidado facial', 'precio'=>'$510.00', 'estado'=>'Agotado',    'tipo'=>'Producto'],
                        ];
                        @endphp

                        @foreach($fakeProducts as $p)
                        <div class="flex items-center justify-between px-5 py-3.5 hover:bg-gray-50 transition">
                            <div class="flex items-center gap-4">
                                {{-- Placeholder imagen --}}
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center text-sm"
                                     style="background:rgba(228,143,98,.1); color:var(--coral)">
                                    <i class="fa-solid {{ $p['tipo'] === 'Servicio' ? 'fa-spa' : 'fa-box-open' }}"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $p['nombre'] }}</p>
                                    <p class="text-xs text-gray-400">{{ $p['categoria'] }} · {{ $p['tipo'] }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-5">
                                <span class="text-sm font-bold" style="color:var(--ink)">{{ $p['precio'] }}</span>
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                    {{ $p['estado'] === 'Disponible' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-500' }}">
                                    {{ $p['estado'] }}
                                </span>
                                <button onclick="loadEdit({{ $p['id'] }})"
                                    class="text-xs font-bold px-3 py-1.5 rounded-lg border transition hover:opacity-90"
                                    style="border-color:var(--coral); color:var(--coral)">
                                    Editar
                                </button>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>

                {{-- Formulario de edición (aparece al hacer clic en Editar) --}}
                <div id="editForm" class="hidden">
                    <div class="flex items-center justify-between mb-4">
                        <p class="section-title mb-0">Editar producto</p>
                        <button onclick="closeEdit()" class="text-xs text-gray-400 hover:text-gray-600 flex items-center gap-1">
                            <i class="fa-solid fa-xmark"></i> Cerrar
                        </button>
                    </div>

                    <form class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre</label>
                                <input type="text" name="nombre" value="Crema hidratante facial"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Categoría</label>
                                <select name="categoria_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm bg-white transition">
                                    <option selected>Cuidado facial</option>
                                    <option>Cuidado corporal</option>
                                    <option>Spa</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Tipo</label>
                                <select name="tipo" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm bg-white transition">
                                    <option selected>Producto</option>
                                    <option>Servicio</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Estado</label>
                                <select name="estado_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm bg-white transition">
                                    <option selected>Disponible</option>
                                    <option>Agotado</option>
                                    <option>Descontinuado</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Precio</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-semibold">$</span>
                                    <input type="number" step="0.01" name="precio" value="320.00"
                                        class="w-full rounded-lg border border-gray-300 pl-7 pr-3 py-2 text-sm transition">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Cantidad disponible</label>
                                <input type="number" name="cantidad_disponible" value="48"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm transition">
                            </div>

                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Descripción</label>
                            <textarea name="descripcion" rows="3"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm resize-none transition">Crema de uso diario con ácido hialurónico.</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Imágenes</label>
                            <input type="file" name="imagenes[]" multiple accept="image/*"
                                class="w-full text-sm border border-gray-300 rounded-lg p-2 bg-white
                                       file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0
                                       file:text-sm file:font-semibold file:bg-gray-100 file:cursor-pointer
                                       hover:file:bg-gray-200 cursor-pointer">
                        </div>

                        <label class="flex items-center gap-2 cursor-pointer w-fit">
                            <input type="checkbox" name="activo" checked class="w-4 h-4 rounded border-gray-300 accent-(--coral)">
                            <span class="text-sm text-gray-700 font-medium">Producto activo</span>
                        </label>

                        <div class="flex items-center gap-3 pt-1">
                            <button type="submit"
                                class="flex items-center gap-2 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition hover:opacity-90"
                                style="background:var(--coral)">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Guardar cambios
                            </button>
                            <button type="button"
                                class="text-sm font-semibold text-red-400 hover:text-red-600 px-4 py-2.5 rounded-xl border border-red-100 hover:bg-red-50 transition">
                                <i class="fa-solid fa-trash-can mr-1 text-xs"></i>
                                Eliminar
                            </button>
                        </div>

                    </form>
                </div>

            </div>

        </main>
    </div>
</div>

{{-- Overlay mobile --}}
<div id="overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:99;" onclick="closeSidebar()"></div>

<script>
    // ── Sidebar mobile ──
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('overlay');
    document.getElementById('menuToggle').addEventListener('click', () => {
        sidebar.classList.add('open');
        overlay.style.display = 'block';
    });
    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.style.display = 'none';
    }

    // ── Tabs ──
    function switchTab(tab) {
        const isAdd = tab === 'add';
        document.getElementById('panel-add').classList.toggle('hidden', !isAdd);
        document.getElementById('panel-search').classList.toggle('hidden', isAdd);
        document.getElementById('tab-add').className    = (isAdd  ? 'tab-active'   : 'tab-inactive') + ' flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200';
        document.getElementById('tab-search').className = (!isAdd ? 'tab-active'   : 'tab-inactive') + ' flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200';
        if (!isAdd) document.getElementById('editForm').classList.add('hidden');
    }

    // ── Editar ──
    function loadEdit(id) {
        document.getElementById('editForm').classList.remove('hidden');
        document.getElementById('editForm').scrollIntoView({ behavior:'smooth', block:'start' });
    }
    function closeEdit() {
        document.getElementById('editForm').classList.add('hidden');
    }

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

    // ── Búsqueda (placeholder) ──
    function doSearch() {
        // Aquí irá la lógica real con fetch/Livewire
        console.log('Buscando:', document.getElementById('searchInput').value);
    }
</script>

@endsection
