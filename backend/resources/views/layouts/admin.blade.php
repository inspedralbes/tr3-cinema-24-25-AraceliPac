<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panell d'Administració - Cinema</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        body {
            background-color: #f3f4f6;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            flex-direction: row;
        }

        .sidebar {
            background-color: #800040;
            color: white;
            width: 250px;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            display: none;
        }

        .sidebar h2 {
            padding: 20px;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .sidebar nav ul {
            list-style: none;
            padding: 0;
        }

        .sidebar nav ul li {
            margin: 0;
        }

        .sidebar nav ul li a,
        .sidebar nav ul li button {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            text-decoration: none;
            color: white;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-size: 1rem;
            /* Tamaño de fuente uniforme */
            box-sizing: border-box;
        }

        .sidebar nav ul li a:hover,
        .sidebar nav ul li button:hover {
            background-color: #600030;
            border-radius: 4px;
        }

        .sidebar nav ul li svg {
            margin-right: 10px;
            width: 20px;
            height: 20px;
        }

        .seccion {
            margin-top: 20px;
        }

        /* Styles for mobile */
        @media (max-width: 768px) {
            .sidebar {
                display: none;
                position: fixed;
                top: 0;
                left: -250px;
                transition: left 0.3s;
            }

            .sidebar.show {
                display: block;
                left: 0;
            }

            .container {
                flex-direction: column;
            }

            .menu-toggle {
                display: block;
                background-color: #800040;
                color: white;
                padding: 15px;
                cursor: pointer;
                text-align: center;
                font-size: 18px;
                width: 100%;
            }
        }

        /* Styles for desktop */
        @media (min-width: 769px) {
            .sidebar {
                display: block;
                position: fixed;
                top: 0;
                left: 0;
            }

            .menu-toggle {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Button to toggle sidebar on mobile -->
        <div class="menu-toggle" id="menu-toggle">☰ Menu</div>

        <div class="sidebar" id="sidebar">
            <h2>Cinema Admin</h2>
            <nav>
                <ul>
                    <li class="seccion">
                        <a href="{{ url('/admin') }}" class="{{ request()->is('admin') ? 'active' : '' }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li class="seccion">
                        <form action="{{ route('admin.logout') }}" method="POST" id="logout-form">
                            @csrf
                            <button type="submit" id="logout-btn">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Tancar sessió
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <script>
        // Toggle sidebar visibility on mobile
        document.getElementById("menu-toggle").addEventListener("click", function() {
            document.getElementById("sidebar").classList.toggle("show");
        });
    </script>
</body>

</html>