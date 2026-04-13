{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layout.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;900&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

@vite('resources/css/dashboardAdmin.css')


<div class="adm-wrap">

    {{-- Sidebar --}}
    <aside class="adm-sidebar" id="sidebar">

        <div class="sidebar-brand">
            <div class="brand-icon"><i class="fa-solid fa-heart-pulse"></i></div>
            <div>
                <div class="brand-text">Vida Plena</div>
                <div class="brand-sub">Admin Panel</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-title">General</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item active">
                <i class="fa-solid fa-gauge"></i> Dashboard
            </a>

            @php $perms = session('admin_perms', []); @endphp

            @if(!empty($perms['puede_productos']))
            <div class="nav-section-title">Catálogo</div>
            <a href="#" class="nav-item">
                <i class="fa-solid fa-box-open"></i> Productos
            </a>
            @endif

            @if(!empty($perms['puede_servicios']))
            <a href="#" class="nav-item">
                <i class="fa-solid fa-spa"></i> Servicios
            </a>
            @endif

            @if(!empty($perms['puede_categorias']))
            <a href="#" class="nav-item">
                <i class="fa-solid fa-tags"></i> Categorías
            </a>
            @endif

            @if(!empty($perms['puede_pedidos']))
            <div class="nav-section-title">Ventas</div>
            <a href="#" class="nav-item">
                <i class="fa-solid fa-bag-shopping"></i> Pedidos
            </a>
            @endif

            @if(!empty($perms['puede_usuarios']))
            <div class="nav-section-title">Usuarios</div>
            <a href="#" class="nav-item">
                <i class="fa-solid fa-users"></i> Clientes
            </a>
            @endif

            @if(!empty($perms['puede_admins']))
            <a href="#" class="nav-item">
                <i class="fa-solid fa-user-shield"></i> Administradores
            </a>
            @endif

            @if(!empty($perms['puede_reportes']))
            <div class="nav-section-title">Reportes</div>
            <a href="#" class="nav-item">
                <i class="fa-solid fa-chart-line"></i> Reportes
            </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <div class="admin-profile">
                <div class="admin-avatar">
                    {{ strtoupper(substr(session('admin_nombre', 'A'), 0, 1)) }}
                </div>
                <div class="admin-info">
                    <div class="admin-name">{{ session('admin_nombre') }}</div>
                    <div class="admin-rol">{{ session('admin_rol') }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn-logout" data-admin-logout>
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

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
                <div class="stat-card">
                    <div class="stat-icon coral"><i class="fa-solid fa-users"></i></div>
                    <div class="stat-value">—</div>
                    <div class="stat-label">Usuarios registrados</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon rose"><i class="fa-solid fa-bag-shopping"></i></div>
                    <div class="stat-value">—</div>
                    <div class="stat-label">Pedidos totales</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon amber"><i class="fa-solid fa-box-open"></i></div>
                    <div class="stat-value">—</div>
                    <div class="stat-label">Productos activos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon ink"><i class="fa-solid fa-spa"></i></div>
                    <div class="stat-value">—</div>
                    <div class="stat-label">Servicios activos</div>
                </div>
            </div>

            {{-- Permisos --}}
            <p class="section-title">Tus permisos</p>
            @php
                $permLabels = [
                    'puede_productos'  => ['Productos',      'fa-box-open'],
                    'puede_servicios'  => ['Servicios',      'fa-spa'],
                    'puede_categorias' => ['Categorías',     'fa-tags'],
                    'puede_usuarios'   => ['Usuarios',       'fa-users'],
                    'puede_admins'     => ['Admins',         'fa-user-shield'],
                    'puede_pedidos'    => ['Pedidos',        'fa-bag-shopping'],
                    'puede_reportes'   => ['Reportes',       'fa-chart-line'],
                ];
            @endphp
            <div class="perms-grid">
                @foreach($permLabels as $key => [$label, $icon])
                    @php $val = !empty($perms[$key]); @endphp
                    <div class="perm-pill {{ $val ? 'on' : 'off' }}">
                        <i class="fa-solid {{ $icon }}"></i>
                        {{ $label }}
                        <i class="fa-solid {{ $val ? 'fa-check' : 'fa-xmark' }}" style="margin-left:auto"></i>
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
