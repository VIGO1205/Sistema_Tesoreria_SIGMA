
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-6 text-gray-800 dark:text-white">
                Registrar Prematrícula - Año Escolar {{ $data['info_prematricula']['periodo']['año_escolar'] }}
            </h2>

            <!-- Información del Alumno -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-blue-800 dark:text-blue-200 mb-2">
                    👤 Información del Alumno
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Nombre:</span>
                        <span class="font-medium text-gray-800 dark:text-white ml-1">
                            {{ $data['alumno']->primer_nombre }} 
                            {{ $data['alumno']->otros_nombres }} 
                            {{ $data['alumno']->apellido_paterno }} 
                            {{ $data['alumno']->apellido_materno }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Código:</span>
                        <span class="font-medium text-gray-800 dark:text-white ml-1">{{ $data['alumno']->codigo_educando }}</span>
                    </div>
                </div>
            </div>

            <!-- Información de Promoción -->
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-green-800 dark:text-green-200 mb-2">
                    📈 Promoción Automática
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    @if($data['info_prematricula']['ultima_matricula'])
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Grado Actual:</span>
                        <span class="font-medium text-gray-800 dark:text-white ml-1">
                            {{ $data['info_prematricula']['ultima_matricula']->grado->nombre_grado }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Sección Actual:</span>
                        <span class="font-medium text-gray-800 dark:text-white ml-1">
                            {{ $data['info_prematricula']['ultima_matricula']->nombreSeccion ?? 'N/A' }}
                        </span>
                    </div>
                    @endif
                    <div class="md:col-span-2">
                        <span class="text-gray-600 dark:text-gray-400">Próximo Grado:</span>
                        <span class="font-bold text-green-700 dark:text-green-300 ml-1 text-base">
                            {{ $data['info_prematricula']['siguiente_grado']->nombre_grado }}
                            ({{ $data['info_prematricula']['siguiente_grado']->nivel->nombre ?? '' }})
                        </span>
                    </div>
                </div>
            </div>

            <!-- Período de Prematrícula -->
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-yellow-800 dark:text-yellow-200 mb-2">
                    📅 Período de Prematrícula
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    <span class="font-medium">Fecha límite:</span> 
                    {{ \Carbon\Carbon::parse($data['info_prematricula']['periodo']['fecha_fin'])->format('d/m/Y') }}
                </p>
            </div>

            <!-- Formulario -->
            <form action="{{ route('familiar_matricula_prematricula_store') }}" method="POST">
                @csrf

                @if($data['secciones_disponibles']->isNotEmpty())
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Sección Preferida (Opcional)
                        </label>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                            <!-- Grado -->
                            <div>
                                <label for="id_grado" class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Grado</label>
                                <select name="id_grado" id="id_grado"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2
                                        bg-white dark:bg-gray-700 text-gray-800 dark:text-white
                                        focus:outline-none focus:ring-2 focus:ring-blue-500">

                                    <option value="">-- Seleccionar grado --</option>

                                    @foreach($data['secciones_disponibles']->groupBy('id_grado') as $idGrado => $grupo)
                                        <option value="{{ $idGrado }}" {{ old('id_grado') == $idGrado ? 'selected' : '' }}>
                                            {{ $grupo->first()->grado->nombre_grado }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_grado')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sección -->
                            <div>
                                <label for="nombreSeccion" class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Sección</label>
                                <select name="nombreSeccion" id="nombreSeccion"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2
                                        bg-white dark:bg-gray-700 text-gray-800 dark:text-white
                                        focus:outline-none focus:ring-2 focus:ring-blue-500">

                                    <option value="">-- Seleccionar sección --</option>

                                    @foreach($data['secciones_disponibles'] as $seccion)
                                        <option value="{{ $seccion->nombreSeccion }}"
                                            {{ old('nombreSeccion') == $seccion->nombreSeccion ? 'selected' : '' }}>
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
                    </div>
                    @endif


                <div class="mb-6">
                    <label for="observaciones" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Observaciones (Opcional)
                    </label>
                    <textarea name="observaciones" id="observaciones" rows="3"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2
                               bg-white dark:bg-gray-700 text-gray-800 dark:text-white
                               focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Ingrese alguna observación relevante...">{{ old('observaciones') }}</textarea>
                    @error('observaciones')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <a href="{{ $data['return'] }}"
                       class="flex-1 text-center bg-gray-500 hover:bg-gray-600 text-white px-6 py-2.5 rounded-md transition-colors">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="flex-1 bg-green-500 hover:bg-green-600 text-white px-6 py-2.5 rounded-md transition-colors font-medium">
                        ✓ Confirmar Prematrícula
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>