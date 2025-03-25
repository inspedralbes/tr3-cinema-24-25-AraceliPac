<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Panel d'Administració de Cinema</title>
    <style>
        body {
            background-color: #f3f4f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            max-width: 28rem;
            width: 100%;
            padding: 2rem;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 0.5rem;
        }

        .title {
            text-align: center;
            font-size: 1.875rem;
            font-weight: bold;
            color: #111827;
        }

        .subtitle {
            margin-top: 0.5rem;
            text-align: center;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .input-group {
            margin-top: 1rem;
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
        }

        .btn {
            width: 100%;
            padding: 0.5rem;
            background-color: #800040;
            color: white;
            font-weight: medium;
            border-radius: 0.375rem;
            margin-top: 1rem;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #600030;
        }

        .alert {
            background-color: #fee2e2;
            border-left: 4px solid #ef4444;
            color: #b91c1c;
            padding: 0.5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="text-center">
            <h2 class="title">Cinema Admin</h2>
            <p class="subtitle">Inicia sessió per accedir al panell d'administració</p>
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
            </div>
            <button type="submit" class="btn">Iniciar sessió</button>
        </form>
    </div>
</body>

</html>