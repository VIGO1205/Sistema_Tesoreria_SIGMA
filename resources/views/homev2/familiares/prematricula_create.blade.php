<div class="p-8 m-4 bg-gray-100 dark:bg-white/[0.03] rounded-2xl">
    <!-- Header -->
    <div class="flex pb-6 justify-between items-center border-b border-gray-200 dark:border-gray-700">
        <div>
            <h2 class="text-2xl font-bold dark:text-gray-200 text-gray-800">Registrar Prematrícula</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Año Escolar {{ $data['info_prematricula']['periodo']['año_escolar'] }} - Promoción automática</p>
        </div>
        <div class="flex gap-3">
            @if($data['secciones_disponibles']->isNotEmpty())
            <input form="form" type="submit"
                class="cursor-pointer inline-flex items-center gap-2 rounded-lg border border-green-300 bg-green-500 px-6 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:border-green-600 dark:bg-green-600 dark:hover:bg-green-700"
                value="✓ Confirmar Prematrícula"
            >
            @endif
            <a href="{{ $data['return'] }}"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
            >
                Cancelar
            </a>
        </div>
    </div>

    <!-- Aviso de Actualización de Datos -->
    <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="font-semibold text-blue-800 dark:text-blue-200">¿Necesita actualizar los datos del alumno?</h4>
                    <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                        Antes de confirmar la prematrícula, puede actualizar información personal, dirección, vivienda y solicitar reubicación de escala.
                    </p>
                </div>
            </div>
            <a href="{{ route('familiar_alumno_actualizar_datos') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-blue-300 bg-blue-500 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 whitespace-nowrap"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Actualizar Datos
            </a>
        </div>
    </div>

    <form method="POST" id="form" action="{{ route('familiar_matricula_prematricula_store') }}" class="mt-8">
        @csrf

        <!-- Grado oculto (promoción automática) -->
        <input type="hidden" name="id_grado" value="{{ $data['info_prematricula']['siguiente_grado']->id_grado }}">

        <!-- Información del Alumno -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Información del Alumno
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4">
                <div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Nombre completo</span>
                    <p class="font-medium text-gray-800 dark:text-gray-200">
                        {{ $data['alumno']->primer_nombre }} 
                        {{ $data['alumno']->otros_nombres }} 
                        {{ $data['alumno']->apellido_paterno }} 
                        {{ $data['alumno']->apellido_materno }}
                    </p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Código educando</span>
                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $data['alumno']->codigo_educando }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Escala</span>
                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $data['info_prematricula']['ultima_matricula']->escala ?? $data['alumno']->escala ?? 'A' }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Dirección</span>
                    <p class="font-medium text-gray-800 dark:text-gray-200 text-sm">{{ $data['alumno']->direccion ?? 'No registrada' }}</p>
                </div>
            </div>
        </div>

        <!-- Información de Promoción -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                Promoción Automática
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                <div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Grado Actual</span>
                    <p class="font-medium text-gray-800 dark:text-gray-200">
                        {{ $data['info_prematricula']['ultima_matricula']->grado->nombre_grado }}
                    </p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Sección Actual</span>
                    <p class="font-medium text-gray-800 dark:text-gray-200">
                        {{ $data['info_prematricula']['ultima_matricula']->nombreSeccion ?? 'N/A' }}
                    </p>
                </div>
                <div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Próximo Grado</span>
                    <p class="font-bold text-green-700 dark:text-green-300">
                        {{ $data['info_prematricula']['siguiente_grado']->nombre_grado }}
                        ({{ $data['info_prematricula']['siguiente_grado']->nivelEducativo->nombre_nivel ?? '' }})
                    </p>
                </div>
            </div>
        </div>

        <!-- Período de Prematrícula -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Período de Prematrícula
            </h3>
            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    <span class="font-medium">Fecha límite:</span> 
                    {{ \Carbon\Carbon::parse($data['info_prematricula']['periodo']['fecha_fin'])->format('d/m/Y') }}
                </p>
            </div>
        </div>

        <!-- Selección de Sección -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Sección
            </h3>
            
            @if($data['secciones_disponibles']->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nombreSeccion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Sección Preferida <span class="text-red-500">*</span>
                    </label>
                    <select name="nombreSeccion" id="nombreSeccion" required
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5
                            bg-white dark:bg-gray-800 text-gray-800 dark:text-white
                            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Seleccionar sección --</option>
                        @foreach($data['secciones_disponibles'] as $seccion)
                            <option value="{{ $seccion->nombreSeccion }}" {{ old('nombreSeccion') == $seccion->nombreSeccion ? 'selected' : '' }}>
                                {{ $seccion->nombreSeccion }}
                            </option>
                        @endforeach
                    </select>
                    @error('nombreSeccion')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                La sección final será asignada por la institución según disponibilidad.
            </p>
            @else
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-red-800 dark:text-red-200">Sin secciones disponibles</h4>
                        <p class="text-sm text-red-700 dark:text-red-300 mt-1">
                            No hay secciones disponibles para el grado {{ $data['info_prematricula']['siguiente_grado']->nombre_grado }}.
                            Por favor, contacte con la institución.
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Observaciones -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Información Adicional
            </h3>
            <div>
                <label for="observaciones" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Observaciones (Opcional)
                </label>
                <textarea name="observaciones" id="observaciones" rows="3"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5
                           bg-white dark:bg-gray-800 text-gray-800 dark:text-white
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Ingrese alguna observación relevante...">{{ old('observaciones') }}</textarea>
                @error('observaciones')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ $data['return'] }}"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Cancelar
            </a>
            @if($data['secciones_disponibles']->isNotEmpty())
            <input form="form" type="submit"
                class="cursor-pointer inline-flex items-center gap-2 rounded-lg border border-green-300 bg-green-500 px-6 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:border-green-600 dark:bg-green-600 dark:hover:bg-green-700"
                value="📋 Confirmar Prematrícula"
            >
            @endif
        </div>
    </form>
</div>