<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIBOT | Login</title>
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.7);
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --input-bg: rgba(15, 23, 42, 0.6);
            --border-color: #334155;
            --error-color: #ef4444;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: var(--bg-color);
            background-image: radial-gradient(circle at 50% -20%, #1e3a8a, var(--bg-color) 60%);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            background: var(--card-bg);
            border-radius: 1rem;
            backdrop-filter: blur(12px);
            border: 1px solid var(--border-color);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-area {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-area h1 {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 2px;
            color: var(--primary);
            text-transform: uppercase;
        }

        .logo-area p {
            color: var(--text-muted);
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-muted);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            background-color: var(--input-bg);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            color: var(--text-main);
            font-size: 1rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        }

        .btn-submit {
            width: 100%;
            padding: 0.875rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.1s;
            margin-top: 1rem;
        }

        .btn-submit:hover {
            background-color: var(--primary-hover);
        }

        .btn-submit:active {
            transform: scale(0.98);
        }

        .error-message {
            color: var(--error-color);
            background-color: rgba(239, 68, 68, 0.1);
            padding: 0.75rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="logo-area">
            <div class="sidebar-header">
                <img src="{{ asset('img/logo_login.png') }}" alt="Logo UNES"
                    style="width: 200px; height: 200px; border-radius: 5px; object-fit: cover;">
            </div>
            <h1>SVCU</h1>
            <p>Sistema de verificacion cuidadana ubicua</p>
        </div>

        @if($errors->any())
            <div class="error-message">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="form-group">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" class="form-control" value="{{ old('usuario') }}"
                    required autofocus placeholder="Ingresa tu usuario">
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" required
                    placeholder="••••••••">
            </div>

            <button type="submit" class="btn-submit">Ingresar al Sistema</button>
        </form>
    </div>

</body>

</html>