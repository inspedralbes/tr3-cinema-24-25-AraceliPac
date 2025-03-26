<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cinema Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f8f9fa;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #800040;
            color: white;
            padding: 60px 0;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 3rem;
            margin: 0;
            padding: 0;
        }

        .subtitle {
            font-size: 1.5rem;
            opacity: 0.8;
            margin-top: 10px;
        }

        .content {
            padding: 40px 20px;
            text-align: center;
        }

        .buttons {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 40px;
        }

        .button {
            display: inline-block;
            padding: 15px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }

        .primary {
            background-color: #800040;
            color: white;
            box-shadow: 0 4px 6px rgba(128, 0, 64, 0.2);
        }

        .primary:hover {
            background-color: #6a0035;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(128, 0, 64, 0.3);
        }

        .secondary {
            background-color: white;
            color: #800040;
            border: 2px solid #800040;
        }

        .secondary:hover {
            background-color: #f8f0f4;
            transform: translateY(-2px);
        }

        .features {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            margin: 60px 0;
        }

        .feature {
            flex: 1;
            min-width: 250px;
            max-width: 350px;
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #800040;
        }

        .feature h3 {
            margin-top: 0;
            color: #800040;
        }

        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }

        @media (max-width: 768px) {
            .feature {
                min-width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="container">
            <h1>Cinema Manager</h1>
            <div class="subtitle">Gestiona el teu cinema de manera eficient</div>
        </div>
    </div>

    <div class="content container">
        <h2>Benvingut a la Plataforma de Gestió de Cinema Pedralbes</h2>
        <p>Una solució completa per administrar pel·lícules, projeccions, entrades i més.</p>

        <div class="buttons">
            <a href="/login" class="button primary">Accedir a l'Administració</a>
            
        </div>

        <div class="features">
            <div class="feature">
                <div class="feature-icon">🎬</div>
                <h3>Gestió de Pel·lícules</h3>
                <p>Administra fàcilment la teva cartellera de pel·lícules, amb tota la informació necessària.</p>
            </div>
            <div class="feature">
                <div class="feature-icon">📅</div>
                <h3>Projeccions</h3>
                <p>Planifica i gestiona les projeccions per a cada pel·lícula, amb horaris i sales.</p>
            </div>
            <div class="feature">
                <div class="feature-icon">🎟️</div>
                <h3>Entrades</h3>
                <p>Sistema complet per a la gestió de venda d'entrades i control d'aforament.</p>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} Cinema Manager. Tots els drets reservats.</p>
        </div>
    </div>
</body>

</html>