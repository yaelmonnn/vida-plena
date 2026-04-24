{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layout.admin')

@section('content')


<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;900&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

@vite('resources/css/dashboardAdmin.css')


<div class="adm-wrap">

    {{-- Sidebar --}}
    <x-admin.sidebar :modulos="$modulos" />

    {{-- Main --}}
    <div class="adm-main">

        <header class="adm-topbar">
            <div style="display:flex; align-items:center; gap:12px;">
                <button class="menu-toggle" id="menuToggle" aria-label="Menú">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <span class="topbar-title">Dashboard</span>
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
                <div style="background:#e6f9f0; color:#1b7a52; border:1px solid #b7ebd3; padding:12px 16px; border-radius:10px; font-size:.85rem; display:flex; align-items:center; gap:8px; margin-bottom:1.5rem;">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Welcome --}}
            <div class="welcome-card">
                <div class="welcome-text">
                    <h2>Hola, {{ session('admin_nombre') }}</h2>
                    <p>Tienes acceso como <strong style="color:var(--coral)">{{ session('admin_rol') }}</strong>. Aquí está el resumen del sistema.</p>
                </div>
                <div class="welcome-icon">
                    <i class="fa-solid fa-shield-halved" style="color:rgba(255,255,255,.3)"></i>
                </div>
            </div>

            {{-- Stats --}}
            <div class="stats-grid">

                <x-admin.stat-card
                    icon="fa-users"
                    :value="$totalUsuarios"
                    label="Usuarios registrados"
                    color="coral"
                />

                <x-admin.stat-card
                    icon="fa-bag-shopping"
                    value="_"
                    label="Pedidos totales"
                    color="rose"
                />

                <x-admin.stat-card
                    icon="fa-box-open"
                    :value="$totalProductos"
                    label="Productos activos"
                    color="amber"
                />

                <x-admin.stat-card
                    icon="fa-spa"
                    :value="$totalServicios"
                    label="Servicios activos"
                    color="ink"
                />

            </div>

            {{-- Permisos --}}
            <p class="section-title">Tus permisos</p>
            <div class="perms-grid">
                @foreach($modulos as $mod)

                    <div class="perm-pill on">
                        <i class="{{ $mod->icono }}"></i>
                        {{ $mod->modulo }}
                        <i class="fa-solid fa-check" style="margin-left:auto"></i>
                    </div>

                @endforeach
            </div>

        </main>
    </div>
</div>

{{-- Overlay mobile --}}
<div id="overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:99;" onclick="closeSidebar()"></div>

<script>
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
</script>

@endsection
