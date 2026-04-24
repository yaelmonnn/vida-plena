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
        <a href="{{ route('admin.dashboard') }}" class="nav-item">
            <i class="fa-solid fa-gauge"></i> Dashboard
        </a>

        @php
            $modulosAgrupados = collect($modulos)->groupBy('categoria');
        @endphp

        @foreach($modulosAgrupados as $categoria => $items)

            <div class="nav-section-title">{{ $categoria }}</div>

            @foreach($items as $mod)
                @if(Route::has($mod->ruta))
                    <a href="{{ route($mod->ruta) }}" class="nav-item">
                        <i class="{{ $mod->icono }}"></i> {{ $mod->modulo }}
                    </a>
                @endif
            @endforeach

        @endforeach

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
