<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Panel d'Administració de Cinema</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f8f9fa;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background-color: #800040;
            color: white;
            padding: 30px 0;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 2.5rem;
            margin: 0;
            padding: 0;
        }

        .subtitle {
            font-size: 1.2rem;
            opacity: 0.8;
            margin-top: 10px;
        }

        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-container {
            max-width: 28rem;
            width: 100%;
            padding: 2rem;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 0.5rem;
        }

        .login-title {
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: #111827;
        }

        .login-subtitle {
            margin-top: 0.5rem;
            text-align: center;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .input-group {
            margin-top: 1rem;
            position: relative;
        }

        .input-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
        }

        .input-field {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            margin-top: 0.25rem;
            box-sizing: border-box;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 33px;
            cursor: pointer;
            user-select: none;
        }

        .btn {
            width: 100%;
            padding: 0.75rem;
            background-color: #800040;
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 0.375rem;
            margin-top: 1.5rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #600030;
        }

        .alert {
            background-color: #fee2e2;
            border-left: 4px solid #ef4444;
            color: #b91c1c;
            padding: 0.75rem;
            margin-bottom: 1.5rem;
            border-radius: 0.25rem;
        }

        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px;
        }

        @media (max-width: 640px) {
            .login-container {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            <h1>Cinema Manager</h1>
            <div class="subtitle">Gestiona el teu cinema de manera eficient</div>
        </div>
    </div>

    <div class="main-content">
        <div class="login-container">
            <div>
                <h2 class="login-title">Accés al Panel</h2>
                <p class="login-subtitle">Inicia sessió per accedir al panell d'administració</p>
            </div>

            @if(session('error'))
            <div class="alert" role="alert">
                <p>{{ session('error') }}</p>
            </div>
            @endif

            <form id="loginForm" method="POST" action="{{ route('admin.login') }}">
                @csrf
                <div class="input-group">
                    <label for="email" class="input-label">Correu electrònic</label>
                    <input id="email" name="email" type="email" required class="input-field">
                </div>

                <div class="input-group">
                    <label for="password" class="input-label">Contrasenya</label>
                    <input id="password" name="password" type="password" required class="input-field">
                    <span class="password-toggle" onclick="togglePassword()">👁️</span>
                </div>
                <button type="submit" class="btn">Iniciar sessió</button>
            </form>
        </div>
    </div>

    <div class="footer">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            <p>&copy; {{ date('Y') }} Cinema Manager. Tots els drets reservats.</p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        }
    </script>
</body>

</html>