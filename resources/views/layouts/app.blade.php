<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIBOT - @yield('title', 'Dashboard')</title>
    <!-- Use Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-body: #0f172a;
            --bg-sidebar: #1e293b;
            --bg-card: rgba(30, 41, 59, 0.7);
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: #334155;
            --header-bg: rgba(15, 23, 42, 0.8);
            --modal-bg: rgba(15, 23, 42, 0.5);
        }

        .light-mode {
            --bg-body: #f1f5f9;
            --bg-sidebar: #ffffff;
            --bg-card: rgba(255, 255, 255, 0.9);
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --header-bg: rgba(255, 255, 255, 0.9);
            --modal-bg: rgba(255, 255, 255, 0.8);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background-color: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }

        .sidebar-header {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-header h2 {
            color: var(--primary);
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: 1px;
        }

        .sidebar-menu {
            padding: 1rem 0;
            flex-grow: 1;
            overflow-y: auto;
        }

        .menu-item {
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
            cursor: pointer;
            width: 100%;
            text-align: left;
            background: none;
            font-size: 1rem;
        }

        .menu-item:hover,
        .menu-item.active {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--text-main);
            border-left-color: var(--primary);
        }

        .menu-item i {
            width: 20px;
            text-align: center;
        }

        .menu-item .chevron {
            margin-left: auto;
            transition: transform 0.3s ease;
            font-size: 0.8rem;
        }

        .menu-item.open .chevron {
            transform: rotate(180deg);
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            background-color: rgba(15, 23, 42, 0.3);
        }

        .submenu.open {
            max-height: 300px;
            /* Suficiente para acomodar elementos */
        }

        .submenu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.6rem 1.5rem 0.6rem 3.5rem;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .submenu a:hover,
        .submenu a.active {
            color: var(--primary);
        }

        .submenu a i {
            font-size: 0.5rem;
        }

        /* Main Content */
        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Header */
        .top-header {
            height: 100px;
            background: var(--header-bg);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 2rem;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: capitalize;
        }

        .btn-logout {
            background: none;
            border: none;
            color: #ef4444;
            cursor: pointer;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: color 0.2s;
        }

        .btn-logout:hover {
            color: #b91c1c;
        }

        /* Content Area */
        .content-area {
            flex-grow: 1;
            padding: 2rem;
            overflow-y: auto;
            background-image: radial-gradient(circle at top right, rgba(30, 58, 138, 0.2), transparent 40%);
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 1.5rem;
            backdrop-filter: blur(8px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        /* Modals */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background-color: var(--modal-bg);
            backdrop-filter: blur(15px);
            /* Efecto Glassmorphism */
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.75rem;
            width: 90%;
            max-height: 90vh;
            /* Adaptable al contenido pero no excede la pantalla */
            max-width: 1200px;
            display: flex;
            flex-direction: column;
            transform: scale(0.95);
            transition: all 0.3s ease;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .modal-overlay.active .modal-content {
            transform: scale(1);
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header h3 {
            font-size: 1.25rem;
            color: var(--primary);
        }

        .btn-close {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1.25rem;
            cursor: pointer;
            transition: color 0.2s;
        }

        .btn-close:hover {
            color: var(--error-color, #ef4444);
        }

        .modal-body {
            padding: 1.5rem;
            overflow-y: auto;
            flex-grow: 1;
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        /* Botones Generales */
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-secondary {
            background-color: var(--border-color);
            color: var(--text-main);
        }

        .btn-secondary:hover {
            background-color: #475569;
        }

        @yield('styles')
    </style>
</head>

<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('img/logo_unes.png') }}" alt="Logo UNES"
                style="width: 100px; height: 100px; border-radius: 4px; object-fit: cover;">
            <h2>SVCU</h2>
        </div>

        <div class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i>
                <span>Inicio</span>
            </a>

            <!-- Módulo 1: Consultas -->
            @php $isConsulta = request()->routeIs('consultas.*'); @endphp
            <button class="menu-item {{ $isConsulta ? 'open active' : '' }}" onclick="toggleSubmenu(this)">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span>Consultas</span>
                <i class="fa-solid fa-chevron-down chevron"></i>
            </button>
            <div class="submenu {{ $isConsulta ? 'open' : '' }}">
                <a href="{{ route('consultas.personas') }}"
                    class="{{ request()->routeIs('consultas.personas') ? 'active' : '' }}">
                    <i class="fa-solid fa-circle"></i> Personas
                </a>
                <a href="{{ route('consultas.vehiculos') }}"
                    class="{{ request()->routeIs('consultas.vehiculos') ? 'active' : '' }}">
                    <i class="fa-solid fa-circle"></i> Vehículos
                </a>
            </div>

            <!-- Módulo 2: Registro de Personas (Solo SIPOL) -->
            @if(Auth::user()->rolObj->nombre_rol === 'sipol')
                @php $isPersona = request()->routeIs('personas.*') || request()->routeIs('delitos.*') || request()->routeIs('historial.*'); @endphp
                <button class="menu-item {{ $isPersona ? 'open active' : '' }}" onclick="toggleSubmenu(this)">
                    <i class="fa-solid fa-users-gear"></i>
                    <span>Reg. Personas</span>
                    <i class="fa-solid fa-chevron-down chevron"></i>
                </button>
                <div class="submenu {{ $isPersona ? 'open' : '' }}">
                    <a href="{{ route('personas.index') }}" class="{{ request()->routeIs('personas.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle"></i> Personas
                    </a>
                    <a href="{{ route('delitos.index') }}" class="{{ request()->routeIs('delitos.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle"></i> Delitos
                    </a>
                    <a href="{{ route('historial.index') }}"
                        class="{{ request()->routeIs('historial.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle"></i> Historial Delictivo
                    </a>
                </div>
            @endif

            <!-- Módulo 3: Registro de Vehículos (Solo SIPOL) -->
            @if(Auth::user()->rolObj->nombre_rol === 'sipol')
                @php $isVehiculo = request()->routeIs('vehiculos.*') || request()->routeIs('marcas.*') || request()->routeIs('reportes.*'); @endphp
                <button class="menu-item {{ $isVehiculo ? 'open active' : '' }}" onclick="toggleSubmenu(this)">
                    <i class="fa-solid fa-car-on"></i>
                    <span>Reg. Vehículos</span>
                    <i class="fa-solid fa-chevron-down chevron"></i>
                </button>
                <div class="submenu {{ $isVehiculo ? 'open' : '' }}">
                    <a href="{{ route('vehiculos.index') }}"
                        class="{{ request()->routeIs('vehiculos.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle"></i> Vehículos
                    </a>
                    <a href="{{ route('marcas.index') }}" class="{{ request()->routeIs('marcas.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle"></i> Marcas
                    </a>
                    <a href="{{ route('reportes.index') }}" class="{{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle"></i> Reportes
                    </a>
                </div>
            @endif

            <!-- Módulo 4: Ubicaciones (Solo Comisario) -->
            @if(Auth::user()->rolObj->nombre_rol === 'comisario')
                <a href="{{ route('ubicaciones.index') }}"
                    class="menu-item {{ request()->routeIs('ubicaciones.*') || request()->routeIs('estados.*') || request()->routeIs('municipios.*') || request()->routeIs('parroquias.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-map-location-dot"></i>
                    <span>Ubicaciones</span>
                </a>
            @endif

            <!-- Módulo 5: Gestión -->
            @php $isGestion = request()->routeIs('configuracion.*') || request()->routeIs('policias.*') || request()->routeIs('usuarios.*'); @endphp
            <button class="menu-item {{ $isGestion ? 'open active' : '' }}" onclick="toggleSubmenu(this)">
                <i class="fa-solid fa-gears"></i>
                <span>Gestión</span>
                <i class="fa-solid fa-chevron-down chevron"></i>
            </button>
            <div class="submenu {{ $isGestion ? 'open' : '' }}">
                <a href="{{ route('configuracion.index') }}"
                    class="{{ request()->routeIs('configuracion.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-circle"></i> Configuración
                </a>
                @if(Auth::user()->rolObj->nombre_rol === 'comisario')
                    <a href="{{ route('policias.index') }}" class="{{ request()->routeIs('policias.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle"></i> Policías
                    </a>
                    <a href="{{ route('usuarios.index') }}" class="{{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle"></i> Usuarios
                    </a>
                @endif
            </div>

        </div>
    </aside>

    <main class="main-content">
        <header class="top-header">
            <div class="header-membrete"
                style="flex: 1; display: flex; justify-content: center; align-items: center; overflow: hidden; margin-right: 1.5rem;">
                <img src="{{ asset('img/unes_membrete.png') }}" alt="UNES"
                    style="width: 100%; height: 250px; object-fit: fill;">
            </div>
            <div class="user-profile">
                @if(Auth::user()->Foto)
                    <img src="{{ asset(Auth::user()->Foto) }}" alt="Foto"
                        style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary);">
                @else
                    <div
                        style="width: 35px; height: 35px; border-radius: 50%; background: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem;">
                        {{ strtoupper(substr(Auth::user()->usuario, 0, 1)) }}
                    </div>
                @endif
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->usuario }}</div>
                    <div class="user-role">{{ Auth::user()->rolObj->nombre_rol ?? 'Usuario' }}</div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout" title="Cerrar Sesión">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </header>

        <div class="content-area">
            @if(session('success'))
                <div
                    style="background: rgba(16, 185, 129, 0.2); color: #10b981; padding: 1rem; border-radius: 0.5rem; border: 1px solid #10b981; margin-bottom: 1.5rem;">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div
                    style="background: rgba(239, 68, 68, 0.2); color: #ef4444; padding: 1rem; border-radius: 0.5rem; border: 1px solid #ef4444; margin-bottom: 1.5rem;">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        function toggleSubmenu(button) {
            button.classList.toggle('open');
            let submenu = button.nextElementSibling;
            if (submenu.classList.contains('open')) {
                submenu.classList.remove('open');
            } else {
                submenu.classList.add('open');
            }
        }

        // Sistema de Modo Oscuro / Claro
        function toggleTheme() {
            if (document.body.classList.contains('light-mode')) {
                document.body.classList.remove('light-mode');
                localStorage.setItem('theme', 'dark');
            } else {
                document.body.classList.add('light-mode');
                localStorage.setItem('theme', 'light');
            }
        }

        // Cargar preferencia al iniciar
        if (localStorage.getItem('theme') === 'light') {
            document.body.classList.add('light-mode');
        }
    </script>
    @yield('scripts')
</body>

</html>