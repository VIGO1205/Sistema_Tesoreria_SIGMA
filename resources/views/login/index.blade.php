<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colegio Sigma - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">

    @vite(['resources/css/login.css'])
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="brand flex align-center">
                <img style="height: 2rem; display:inline" src="{{ asset('images/sigma_logo.png') }}" alt="Sigma LOGO">
                <h1 style="display:inline">SIGMA</h1>
            </div>
            <div class="welcome-text">
                <h2>Bienvenido,</h2>
                <p>por favor, introduce tus datos</p>
            </div>
            <form id="loginForm" method="POST">
                @csrf
                @error('username')
                    <p>{{ $message }}</p>
                @enderror

                @error('password')
                    <p>{{ $message }}</p>
                @enderror
            
                <div class="form-group">
                    <label for="username">Nombre de usuario</label>
                    <input @error('username') class="invalid-input" @enderror type="text" id="username" name="username" value="{{ old('username') }}" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input @error('password') class="invalid-input" @enderror type="password" id="password" name="password" required>
                </div>
                <div class="forgot-password">
                    <a href="#">¿Olvidó su contraseña?</a>
                </div>
                <button type="submit" class="btn-login">Iniciar sesión</button>
            </form>

            <!-- Separador -->
            <div style="text-align: center; margin: 1.5rem 0; position: relative;">
                <div style="position: absolute; top: 50%; left: 0; right: 0; height: 1px; background: #ddd;"></div>
                <span style="background: white; padding: 0 1rem; position: relative; color: #666; font-size: 0.9rem;">o</span>
            </div>

            <!-- Botón de Pasarela de Pagos -->
            <a href="{{ route('pasarela.index') }}" class="btn-pasarela">
                <svg style="width: 20px; height: 20px; margin-right: 8px;" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                </svg>
                Pagar en Línea
            </a>
            <p style="text-align: center; font-size: 0.85rem; color: #666; margin-top: 0.5rem;">
                Realiza tus pagos de forma rápida y segura
            </p>

            <div class="footer">
                <p>&copy; 2025</p>
            </div>
        </div>
        <div class="image-container" style="background-image: url({{ asset('images/login/fondo.jpg') }});">
        </div>
    </div>
</body>
</html>