<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administració de Cinema</title>
    <!-- Estilos básicos sin CDN -->
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
        }

        .sidebar h4 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar a {
            color: #f8f9fa;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
            transition: all 0.3s;
        }

        .sidebar a:hover {
            background-color: #D4AF37;
        }

        .sidebar .active {
            background-color: #540b03;
        }

        /* Contenido principal */
        .main-content {
            flex: 1;
            padding: 20px;
        }

        .main-header {
            padding: 15px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin-bottom: 0.5rem;
        }

        /* Tarjetas para los paneles */
        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            color: white;
            padding: 10px 15px;
            background-color: #800040;
            border-bottom: 1px solid #ddd;
        }

        .card-body {
            padding: 15px;
        }

        /* Columnas responsivas */
        .col-md-4 {
            width: 33.333%;
            padding: 0 15px;
        }

        .col-md-3 {
            width: 25%;
            padding: 0 15px;
        }

        /* Estilo para móviles */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                min-height: auto;
                margin-bottom: 20px;
            }

            .row {
                flex-direction: column;
            }

            .col-md-4,
            .col-md-3 {
                width: 100%;
                padding: 0 10px;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="sidebar">
                <h4>Cinema Pedralbes</h4>
                <ul>
                    <li>
                        <a href="/admin" class="active">Inici</a>
                    </li>
                    <li>
                        <a href="/admin/pelicules">Pel·lícules</a>
                    </li>
                    <li>
                        <a href="/admin/projeccions">Projeccions</a>
                    </li>
                    <li>
                        <a href="/admin/sales">Sales</a>
                    </li>
                    <li>
                        <a href="/admin/entrades">Entrades</a>
                    </li>
                    <li>
                        <a href="/admin/usuaris">Usuaris</a>
                    </li>
                    <li>
                        <a href="/admin/configuracio">Configuració</a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <main class="main-content">
                <!-- Header -->
                <div class="main-header">
                    <h1>@yield('title', 'Panell d\'Administració')</h1>
                </div>

                <!-- Content -->
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>