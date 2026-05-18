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
                    Clientes
                </span>

            </div>

        </header>

        <main class="adm-content">

            <p class="section-title">
                Gestión de clientes
            </p>

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
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($clientes as $c)

                            <tr>

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

                                <td>{{ $c->telefono }}</td>

                                <td>

                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                        {{ $c->activo ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-500' }}">

                                        {{ $c->activo ? 'Activo' : 'Inactivo' }}

                                    </span>

                                </td>

                            </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </main>

    </div>

</div>

<script>

new DataTable('#clientesTable');

</script>

@endsection
