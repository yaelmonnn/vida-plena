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

                <span class="topbar-title">
                    Administradores
                </span>

            </div>

        </header>

        <main class="adm-content">

            <p class="section-title">
                Gestión de administradores
            </p>

            {{-- TABS --}}

            <div class="inline-flex items-center bg-white border border-gray-200 rounded-xl p-1 mb-6 shadow-sm">

                <button id="tab-search"
                    onclick="switchTab('search')"
                    class="tab-active flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold">

                    <i class="fa-solid fa-users"></i>
                    Administradores

                </button>

                <button id="tab-add"
                    onclick="switchTab('add')"
                    class="tab-inactive flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold">

                    <i class="fa-solid fa-plus"></i>
                    Agregar administrador

                </button>

            </div>

            {{-- TABLA --}}

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

                                @foreach($usuarios as $u)

                                <tr
                                    data-id="{{ $u->Id }}"
                                    data-nombre="{{ $u->nombre }}"
                                    data-apellido="{{ $u->apellido }}"
                                    data-email="{{ $u->email }}"
                                    data-rol="{{ $u->rol_id }}"
                                    data-activo="{{ $u->activo }}"
                                >

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

                                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                            {{ $u->activo ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-500' }}">

                                            {{ $u->activo ? 'Activo' : 'Inactivo' }}

                                        </span>

                                    </td>

                                    <td>

                                        <button
                                            onclick="loadEdit({{ $u->Id }})"
                                            class="text-xs font-bold px-3 py-1.5 rounded-lg border"
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

            {{-- FORMULARIO --}}

            <div id="panel-add" class="hidden">

                <form
                    id="formAdmin"
                    method="POST"
                    action="{{ route('admin.usuarios.store') }}"
                    class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm space-y-6">

                    @csrf

                    <input type="hidden" name="_method" id="form-method" value="POST">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Nombre
                            </label>

                            <input type="text"
                                name="nombre"
                                id="f-nombre"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">

                        </div>

                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Apellido
                            </label>

                            <input type="text"
                                name="apellido"
                                id="f-apellido"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">

                        </div>

                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Correo
                            </label>

                            <input type="email"
                                name="email"
                                id="f-email"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">

                        </div>

                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Contraseña
                            </label>

                            <input type="password"
                                name="password"
                                id="f-password"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">

                        </div>

                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Rol
                            </label>

                            <select
                                name="rol_id"
                                id="f-rol"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">

                                @foreach($roles as $rol)

                                <option value="{{ $rol->Id }}">
                                    {{ $rol->nombre }}
                                </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                    <label class="flex items-center gap-2">

                        <input type="checkbox"
                            name="activo"
                            id="f-activo"
                            checked>

                        <span class="text-sm">
                            Administrador activo
                        </span>

                    </label>

                    <div class="flex gap-3">

                        <button type="submit"
                            class="text-white text-sm font-bold px-6 py-2.5 rounded-xl"
                            style="background:var(--coral)">

                            Guardar administrador

                        </button>

                        <button
                            type="button"
                            id="btnEliminar"
                            class="hidden text-red-500 text-sm font-semibold">

                            Dar de baja

                        </button>

                    </div>

                </form>

                <form id="formEliminar" method="POST" class="hidden">

                    @csrf
                    @method('DELETE')

                </form>

            </div>

        </main>

    </div>

</div>

<script>

new DataTable('#myTable');

let modoEdicion = false;

function switchTab(tab)
{
    document.getElementById('panel-search')
        .classList.toggle('hidden', tab !== 'search');

    document.getElementById('panel-add')
        .classList.toggle('hidden', tab !== 'add');
}

function loadEdit(id)
{
    const row = document.querySelector(`tr[data-id="${id}"]`);

    document.getElementById('f-nombre').value = row.dataset.nombre;
    document.getElementById('f-apellido').value = row.dataset.apellido;
    document.getElementById('f-email').value = row.dataset.email;
    document.getElementById('f-rol').value = row.dataset.rol;

    document.getElementById('f-activo').checked =
        row.dataset.activo == 1;

    document.getElementById('formAdmin').action =
        `/admin/usuarios/${id}`;

    document.getElementById('form-method').value = 'PUT';

    document.getElementById('btnEliminar')
        .classList.remove('hidden');

    document.getElementById('btnEliminar').onclick = function()
    {
        Swal.fire({
            title: '¿Dar de baja administrador?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e05c3a',
            confirmButtonText: 'Sí, dar de baja'
        }).then((result) => {

            if(result.isConfirmed)
            {
                const form = document.getElementById('formEliminar');

                form.action = `/admin/usuarios/${id}`;

                form.submit();
            }

        });
    };

    switchTab('add');
}

</script>

@endsection
