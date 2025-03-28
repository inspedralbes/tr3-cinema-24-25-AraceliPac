<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administració de Cinema - @yield('title', 'Panell d\'Administració')</title>
    <style>
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }

        /* Layout principal */
        .container-fluid {
            width: 100%;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background-color: #800040;
            color: white;
            padding: 20px 0;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            text-align: center;
            margin-bottom: 20px;
            padding: 0 15px;
        }

        .sidebar-header h4 {
            color: #D4AF37;
            font-size: 22px;
            margin-bottom: 5px;
        }

        .sidebar-header p {
            font-size: 14px;
            opacity: 0.8;
        }

        .sidebar nav {
            margin-bottom: 15px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar-link {
            color: #ffffff;
            text-decoration: none;
            display: block;
            padding: 12px 15px;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar-link:hover {
            background-color: rgba(212, 175, 55, 0.2);
            border-left-color: #D4AF37;
        }

        .sidebar-link.active {
            background-color: rgba(212, 175, 55, 0.3);
            border-left-color: #D4AF37;
            font-weight: bold;
        }

        .sidebar-icon {
            display: inline-block;
            width: 20px;
            margin-right: 10px;
            text-align: center;
        }

        .sidebar-divider {
            height: 1px;
            margin: 15px 15px;
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Contenido principal */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
        }

        .main-header {
            padding: 15px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .main-title {
            color: #333;
            font-size: 24px;
            font-weight: bold;
        }

        .user-dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-toggle {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 8px 15px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dropdown-toggle:hover {
            background-color: #e9ecef;
        }

        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 100%;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            min-width: 180px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
            display: none;
            z-index: 1000;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            display: block;
            padding: 8px 15px;
            text-decoration: none;
            color: #333;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .dropdown-divider {
            height: 1px;
            background-color: #ddd;
            margin: 5px 0;
        }

        /* Tarjetas para los paneles */
        .dashboard-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        /* Estilo para móviles */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                min-height: auto;
                position: static;
                padding: 10px 0;
            }

            .main-content {
                margin-left: 0;
            }

            .row {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="sidebar">
                <div class="sidebar-header">
                    <h4>Cinema Pedralbes</h4>
                    <p>Panell d'Administració</p>
                </div>

                <nav>
                    <ul>
                        <li>
                            <a href="{{ url('admin') }}" class="sidebar-link {{ request()->is('admin') || request()->is('admin/home') ? 'active' : '' }}">
                                <span class="sidebar-icon">📊</span> Inici
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('admin/movies') }}" class="sidebar-link {{ request()->is('admin/movies*') ? 'active' : '' }}">
                                <span class="sidebar-icon">🎬</span> Pel·lícules
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('admin/screenings') }}" class="sidebar-link {{ request()->is('admin/screenings*') ? 'active' : '' }}">
                                <span class="sidebar-icon">📽️</span> Projeccions
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('admin/tickets') }}" class="sidebar-link {{ request()->is('admin/tickets*') ? 'active' : '' }}">
                                <span class="sidebar-icon">🎟️</span> Entrades
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('admin/users') }}" class="sidebar-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                                <span class="sidebar-icon">👥</span> Usuaris
                            </a>
                        </li>
                    </ul>
                </nav>

                <div class="sidebar-divider"></div>

                <nav>
                    <ul>
                        <li>
                            <a href="{{ url('admin/settings') }}" class="sidebar-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
                                <span class="sidebar-icon">⚙️</span> Configuracions
                            </a>
                        </li>
                        <li>
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="sidebar-link">
                                <span class="sidebar-icon">🚪</span> Tancar sessió
                            </a>
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Main Content -->
            <main class="main-content">
                <!-- Header -->
                <div class="main-header">
                    <h1 class="main-title">@yield('title', 'Panell d\'Administració')</h1>

                    <div class="user-dropdown">
                        <div class="dropdown-toggle" onclick="toggleDropdown()">
                            <span>{{ Auth::user()->name ?? 'Administrador' }}</span>
                            <span>▼</span>
                        </div>
                        <div class="dropdown-menu" id="userDropdown">
                            <a href="{{ url('admin/profile') }}" class="dropdown-item">El meu perfil</a>
                            <div class="dropdown-divider"></div>
                            <a href="#" onclick="event.preventDefault(); document.getElementById('header-logout-form').submit();" class="dropdown-item">Tancar sessió</a>
                            <form id="header-logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            document.getElementById('userDropdown').classList.toggle('show');
        }

        // Cerrar el dropdown si el usuario hace clic fuera de él
        window.onclick = function(event) {
            if (!event.target.matches('.dropdown-toggle') && !event.target.parentNode.matches('.dropdown-toggle')) {
                var dropdowns = document.getElementsByClassName("dropdown-menu");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>

</html>