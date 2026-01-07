<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prematrícula - Colegio Sigma</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen">
    <!-- Header simple -->
    <header class="bg-white dark:bg-gray-800 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/sigma_logo.png') }}" alt="Logo" class="h-10">
                <div>
                    <h1 class="text-xl font-bold text-gray-800 dark:text-gray-200">SIGMA</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Sistema de Gestión Académica</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600 dark:text-gray-300">
                    {{ auth()->user()->name }}
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400">
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Sidebar mínimo + Contenido -->
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 min-h-screen bg-white dark:bg-gray-800 shadow-sm">
            <nav class="p-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Prematrícula</p>
                <a href="{{ route('pre_apoderado.estado_solicitud') }}" 
                    class="flex items-center gap-3 px-3 py-2 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Estado de Solicitud
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>
</body>
</html>