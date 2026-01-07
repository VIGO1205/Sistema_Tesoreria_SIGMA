<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud Enviada - Colegio Sigma</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Card de éxito -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header verde -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-8 text-center">
                <div class="w-20 h-20 bg-white rounded-full mx-auto flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white">¡Solicitud Enviada!</h1>
                <p class="text-green-100 mt-2">Su solicitud ha sido registrada correctamente</p>
            </div>

            <!-- Contenido -->
            <div class="px-6 py-6">
                <!-- Número de solicitud -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6 text-center">
                    <p class="text-sm text-gray-500">Número de Solicitud</p>
                    <p class="text-2xl font-bold text-gray-800">#{{ session('solicitud_id') }}</p>
                </div>

                <!-- Credenciales -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-blue-800 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                        Sus credenciales de acceso
                    </h3>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center bg-white rounded px-3 py-2">
                            <span class="text-sm text-gray-600">Usuario:</span>
                            <span class="font-mono font-bold text-gray-800">{{ session('usuario') }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-white rounded px-3 py-2">
                            <span class="text-sm text-gray-600">Contraseña:</span>
                            <span class="font-mono font-bold text-gray-800">{{ session('password') }}</span>
                        </div>
                    </div>
                    <p class="text-xs text-blue-600 mt-3">
                        ⚠️ Guarde estas credenciales. Las necesitará para ver el estado de su solicitud.
                    </p>
                </div>

                <!-- Pasos siguientes -->
                <div class="mb-6">
                    <h3 class="font-semibold text-gray-800 mb-3">¿Qué sigue?</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start gap-2">
                            <span class="w-5 h-5 bg-green-100 text-green-600 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold">1</span>
                            <span>Su solicitud será revisada por la institución</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="w-5 h-5 bg-green-100 text-green-600 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold">2</span>
                            <span>Puede iniciar sesión para ver el estado de su solicitud</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="w-5 h-5 bg-green-100 text-green-600 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold">3</span>
                            <span>Cuando sea aprobada, podrá acceder al sistema como apoderado</span>
                        </li>
                    </ul>
                </div>

                <!-- Botón -->
                <a href="{{ route('login') }}" 
                    class="block w-full text-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    Ir a Iniciar Sesión
                </a>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-sm text-gray-500 mt-6">
            &copy; {{ date('Y') }} Colegio Sigma
        </p>
    </div>
</body>
</html>