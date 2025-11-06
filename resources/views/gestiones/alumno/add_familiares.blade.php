@extends('base.administrativo.blank')

@section('titulo')
    Crear Familiar
@endsection

@section('contenido')
    <!-- Información del Alumno -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md mb-8">
        <div class="flex items-center mb-4">
            <svg class="w-6 h-6 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Asignando Familiar al Alumno</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Estás a punto de crear o asignar un familiar al siguiente estudiante. Verifica los datos antes de continuar:</p>
            </div>
        </div>
                
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Código Educando</span>
                <p class="font-semibold text-gray-800 dark:text-white">{{ $data['default']['codigo_educando'] }}</p>
            </div>
            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">DNI</span>
                <p class="font-semibold text-gray-800 dark:text-white">{{ $data['default']['d_n_i'] }}</p>
            </div>
            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Apellidos y Nombres</span>
                <p class="font-semibold text-gray-800 dark:text-white">
                    {{ $data['default']['apellido_paterno'] }} {{ $data['default']['apellido_materno'] }}, {{ $data['default']['primer_nombre'] }} {{ $data['default']['otros_nombres'] }}
                </p>
            </div>
            <div class="space-y-1">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Año de Ingreso</span>
                <p class="font-semibold text-gray-800 dark:text-white">{{ $data['default']['año_ingreso'] }}</p>
            </div>
        </div>
    </div>

    <!-- Formulario Principal -->
    <div class="p-8 m-4 bg-gray-100 dark:bg-white/[0.03] rounded-2xl">
        <!-- Header -->
        <div class="flex pb-6 justify-between items-center border-b border-gray-200 dark:border-gray-700">
            <div>
                <h2 class="text-2xl font-bold dark:text-gray-200 text-gray-800">Nuevo Familiar</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Registra un nuevo familiar en el sistema</p>
            </div>
            <div class="flex gap-3">
                <input form="form" type="submit"
                    id="boton_crear_o_asignar"
                    class="cursor-pointer  items-center gap-2 rounded-lg border hidden border-green-300 bg-green-500 px-6 py-2.5 text-sm font-medium text-white  shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:border-green-600 dark:bg-green-600 dark:hover:bg-green-700"
                    value="👨‍👩‍👧‍👦 Crear y Asignar Familiar"
                >
                <input form="form2" type="submit"
                    id="boton_asignar"
                    class="cursor-pointer inline-flex items-center gap-2 rounded-lg border border-green-300 bg-green-500 px-6 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:border-green-600 dark:bg-green-600 dark:hover:bg-green-700"
                    value="👨‍👩‍👧‍👦 Asignar Familiar"
                >
                <a href="{{ $data['return'] ?? route('familiar.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </a>
            </div>
        </div>

        <!-- Selector de Modo Mejorado -->
        <div class="mb-8">
            <label class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-6 block flex items-center">
                <svg class="w-6 h-6 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                ¿Deseas asignar un familiar ya existente?
            </label>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Opción Crear Nuevo -->
                <label class="relative cursor-pointer group">
                    <input type="radio" id="modo_crear" name="modo_familiar" value="crear" 
                        class="sr-only peer" {{ old('modo_familiar', 'crear') === 'crear' ? 'checked' : '' }}>
                            
                    <div class="relative overflow-hidden bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-2 border-gray-200 dark:border-gray-700 rounded-xl p-6 transition-all duration-300 peer-checked:border-blue-500 peer-checked:bg-gradient-to-br peer-checked:from-blue-100 peer-checked:to-indigo-100 dark:peer-checked:from-blue-900/40 dark:peer-checked:to-indigo-900/40 peer-checked:shadow-lg peer-checked:scale-105 hover:shadow-md hover:scale-102 group-hover:border-blue-300">
                                        
                        <!-- Indicador de selección -->
                        <div class="absolute top-4 right-4 w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-blue-500 peer-checked:bg-blue-500 transition-all duration-300 flex items-center justify-center">
                            <div class="w-2 h-2 bg-white rounded-full scale-0 peer-checked:scale-100 transition-transform duration-300"></div>
                        </div>
                                        
                        <!-- Icono principal -->
                        <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-lg mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                                        
                        <!-- Contenido -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                Crear nuevo familiar
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Registra un familiar completamente nuevo en el sistema con toda su información personal
                            </p>
                        </div>
                    </div>
                </label>
                        
                <!-- Opción Asignar Existente -->
                <label class="relative cursor-pointer group">
                    <input type="radio" id="modo_asignar" name="modo_familiar" value="asignar" 
                        class="sr-only peer" {{ old('modo_familiar') === 'asignar' ? 'checked' : '' }}>
                            
                    <div class="relative overflow-hidden bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 border-2 border-gray-200 dark:border-gray-700 rounded-xl p-6 transition-all duration-300 peer-checked:border-purple-500 peer-checked:bg-gradient-to-br peer-checked:from-purple-100 peer-checked:to-pink-100 dark:peer-checked:from-purple-900/40 dark:peer-checked:to-pink-900/40 peer-checked:shadow-lg peer-checked:scale-105 hover:shadow-md hover:scale-102 group-hover:border-purple-300">
                                        
                        <!-- Indicador de selección -->
                        <div class="absolute top-4 right-4 w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-purple-500 peer-checked:bg-purple-500 transition-all duration-300 flex items-center justify-center">
                            <div class="w-2 h-2 bg-white rounded-full scale-0 peer-checked:scale-100 transition-transform duration-300"></div>
                        </div>
                                        
                        <!-- Icono principal -->
                        <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg shadow-lg mb-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                        </div>
                                        
                        <!-- Contenido -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                                Asignar familiar existente
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Selecciona un familiar que ya está registrado en el sistema para vincularlo
                            </p>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <input type="hidden" name="modo_familiar" id="input_modo_familiar" value="{{ old('modo_familiar', 'crear') }}">

        {{-- 🔥 AQUÍ ESTÁN LAS RUTAS CONDICIONALES --}}
        @if(isset($data['is_session_mode']) && $data['is_session_mode'])
            {{-- Modo sesión: usar rutas especiales para datos temporales --}}
            <form method="POST" id="form2" action="{{ route('alumno_guardar_familiares_session') }}" class="mt-8">
                @csrf
                <div id="asignar_familiar" class="mb-8 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            @include('components.forms.combo', [
                                'label' => 'Familiar Existente',
                                'name' => Str::snake('Familiar Existente'),
                                'error' => $errors->first('Familiar Existente') ?? false,
                                'value' => old(Str::snake('Familiar Existente')),
                                'options' => $data['familiares'],
                                'options_attributes' => ['id', 'nombre_completo']
                            ])
                        </div>
                        <div>
                            @include('components.forms.combo', [
                                'label' => 'Parentesco Del Familiar',
                                'name' => Str::snake('Parentesco Del Familiar'),
                                'error' => $errors->first(Str::snake('Parentesco Del Familiar')) ?? false,
                                'value' => old(Str::snake('Parentesco Del Familiar')),
                                'options' => [
                                    ['id' => 'Padre', 'descripcion' => 'Padre'],
                                    ['id' => 'Madre', 'descripcion' => 'Madre'],
                                    ['id' => 'Tio/a', 'descripcion' => 'Tio/a'],
                                    ['id' => 'Abuelo/a', 'descripcion' => 'Abuelo/a'],
                                    ['id' => 'Apoderado', 'descripcion' => 'Apoderado'],
                                    ['id' => 'Otro', 'descripcion' => 'Otro'],
                                ],
                                'options_attributes' => ['id', 'descripcion']
                            ])
                        </div>
                    </div>
                </div>
            </form>
        @else
            {{-- Modo normal: usar rutas existentes con ID --}}
            <form method="POST" id="form2" action="{{ route('alumno_guardar_familiares', ['id' => $data['id']]) }}" class="mt-8">
                @csrf
                <div id="asignar_familiar" class="mb-8 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            @include('components.forms.combo', [
                                'label' => 'Familiar Existente',
                                'name' => Str::snake('Familiar Existente'),
                                'error' => $errors->first('Familiar Existente') ?? false,
                                'value' => old(Str::snake('Familiar Existente')),
                                'options' => $data['familiares'],
                                'options_attributes' => ['id', 'nombre_completo']
                            ])
                        </div>
                        <div>
                            @include('components.forms.combo', [
                                'label' => 'Parentesco Del Familiar',
                                'name' => Str::snake('Parentesco Del Familiar'),
                                'error' => $errors->first(Str::snake('Parentesco Del Familiar')) ?? false,
                                'value' => old(Str::snake('Parentesco Del Familiar')),
                                'options' => [
                                    ['id' => 'Padre', 'descripcion' => 'Padre'],
                                    ['id' => 'Madre', 'descripcion' => 'Madre'],
                                    ['id' => 'Tio/a', 'descripcion' => 'Tio/a'],
                                    ['id' => 'Abuelo/a', 'descripcion' => 'Abuelo/a'],
                                    ['id' => 'Apoderado', 'descripcion' => 'Apoderado'],
                                    ['id' => 'Otro', 'descripcion' => 'Otro'],
                                ],
                                'options_attributes' => ['id', 'descripcion']
                            ])
                        </div>
                    </div>
                </div>
            </form>
        @endif
                    
        <div id="crear_familiar">
            {{-- 🔥 AQUÍ TAMBIÉN RUTAS CONDICIONALES --}}
            @if(isset($data['is_session_mode']) && $data['is_session_mode'])
                {{-- Modo sesión: usar ruta especial --}}
                <form method="POST" id="form" action="{{ route('alumno_guardar_familiares_session') }}" class="mt-8">
                    @csrf
                    <input type="hidden" name="modo_familiar" id="input_modo_familiar" value="{{ old('modo_familiar', 'crear') }}">
            @else
                {{-- Modo normal: usar ruta existente con ID --}}
                <form method="POST" id="form" action="{{ route('alumno_guardar_familiares', ['id' => $data['id']]) }}" class="mt-8">
                    @csrf
                    <input type="hidden" name="modo_familiar" id="input_modo_familiar" value="{{ old('modo_familiar', 'crear') }}">
            @endif

            <!-- Configuración de Usuario -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Configuración de Acceso
                </h3>
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="crear_usuario" name="crear_usuario" value="1"
                             {{ old('crear_usuario') ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="crear_usuario" class="text-sm font-medium text-blue-800 dark:text-blue-200">
                            ¿Desea crear una cuenta de usuario para este familiar?
                        </label>
                    </div>
                    <p class="text-xs text-blue-600 dark:text-blue-300 mt-2 ml-7">
                        Esto permitirá al familiar acceder al sistema con sus propias credenciales
                    </p>
                </div>
            </div>

            <!-- Información de Identificación -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-4 0V4a2 2 0 014 0v2"></path>
                    </svg>
                    Información de Identificación
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @include('components.forms.string', [
                        'label' => 'DNI',
                        'name' => 'dni',
                        'error' => $errors->first(Str::snake('Dni')) ?? false,
                        'value' => old('dni')
                    ])
                </div>
            </div>

            <!-- Información Personal -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Información Personal
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @include('components.forms.string', [
                        'label' => 'Apellido Paterno',
                        'name' => 'apellido_paterno',
                        'error' => $errors->first(Str::snake('Apellido Paterno')) ?? false,
                        'value' => old('apellido_paterno')
                    ])
                    @include('components.forms.string', [
                        'label' => 'Apellido Materno',
                        'name' => 'apellido_materno',
                        'error' => $errors->first(Str::snake('Apellido Materno')) ?? false,
                        'value' => old('apellido_materno')
                    ])
                    @include('components.forms.string', [
                        'label' => 'Primer Nombre',
                        'name' => 'primer_nombre',
                        'error' => $errors->first(Str::snake('Primer Nombre')) ?? false,
                        'value' => old('primer_nombre')
                    ])
                    @include('components.forms.string', [
                        'label' => 'Otros Nombres',
                        'name' => 'otros_nombres',
                        'error' => $errors->first(Str::snake('Otros Nombres')) ?? false,
                        'value' => old('otros_nombres')
                    ])
                    @include('components.forms.combo', [
                        'label' => 'Parentesco',
                        'name' => Str::snake('Parentesco'),
                        'error' => $errors->first(Str::snake('Parentesco')) ?? false,
                        'value' => old(Str::snake('Parentesco')),
                        'options' => [
                            ['id' => 'Padre', 'descripcion' => 'Padre'],
                            ['id' => 'Madre', 'descripcion' => 'Madre'],
                            ['id' => 'Tio/a', 'descripcion' => 'Tio/a'],
                            ['id' => 'Abuelo/a', 'descripcion' => 'Abuelo/a'],
                            ['id' => 'Apoderado', 'descripcion' => 'Apoderado'],
                            ['id' => 'Otro', 'descripcion' => 'Otro'],
                        ],
                        'options_attributes' => ['id', 'descripcion']
                    ])
                </div>
            </div>

            <!-- Información de Contacto -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    Información de Contacto
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @include('components.forms.string', [
                        'label' => 'Número de Contacto',
                        'name' => 'numero_contacto',
                        'error' => $errors->first(Str::snake('Numero de Contacto')) ?? false,
                        'value' => old('numero_contacto')
                    ])
                    @include('components.forms.string', [
                        'label' => 'Correo Electrónico',
                        'name' => 'correo_electronico',
                        'error' => $errors->first(Str::snake('Correo Electronico')) ?? false,
                        'value' => old('correo_electronico')
                    ])
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ $data['return'] ?? route('familiar.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </a>
                <input form="form" type="submit"
                    class="cursor-pointer inline-flex items-center gap-2 rounded-lg border border-green-300 bg-green-500 px-6 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:border-green-600 dark:bg-green-600 dark:hover:bg-green-700"
                    value="👨‍👩‍👧‍👦 Crear Familiar"
                >
            </div>
            </form>
        </div>
    </div>
@endsection

@section('custom-js')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const crear = document.getElementById("modo_crear")
            const asignar = document.getElementById("modo_asignar")
            const divCrear = document.getElementById("crear_familiar")
            const divAsignar = document.getElementById("asignar_familiar")
            const botonCrearOAsignar = document.getElementById("boton_crear_o_asignar")
            const botonAsignar = document.getElementById("boton_asignar")
            const modoFamiliarInput = document.getElementById("input_modo_familiar")
            const formCrear = document.getElementById("form")
            const formAsignar = document.getElementById("form2")

            function toggleForm() {
                // Agregar transiciones suaves
                if (divCrear) divCrear.style.transition = "all 0.4s cubic-bezier(0.4, 0, 0.2, 1)"
                if (divAsignar) divAsignar.style.transition = "all 0.4s cubic-bezier(0.4, 0, 0.2, 1)"

                if (asignar.checked) {
                    modoFamiliarInput.value = "asignar"
                    if (formAsignar) formAsignar.appendChild(modoFamiliarInput)

                    // Animación suave para ocultar crear y mostrar asignar
                    if (divCrear && divAsignar) {
                        divCrear.style.opacity = "0"
                        divCrear.style.transform = "translateY(-20px) scale(0.95)"

                        setTimeout(() => {
                            divCrear.classList.add("hidden")
                            divAsignar.classList.remove("hidden")
                            divAsignar.style.opacity = "0"
                            divAsignar.style.transform = "translateY(20px) scale(0.95)"

                            requestAnimationFrame(() => {
                                divAsignar.style.opacity = "1"
                                divAsignar.style.transform = "translateY(0) scale(1)"
                            })
                        }, 200)
                    }

                    // Cambiar botones con animación
                    if (botonAsignar && botonCrearOAsignar) {
                        botonCrearOAsignar.style.transform = "scale(0.9)"
                        botonCrearOAsignar.style.opacity = "0"

                        setTimeout(() => {
                            botonAsignar.classList.remove("hidden")
                            botonAsignar.classList.add("inline-flex")
                            botonCrearOAsignar.classList.add("hidden")
                            botonCrearOAsignar.classList.remove("inline-flex")

                            botonAsignar.style.transform = "scale(0.9)"
                            botonAsignar.style.opacity = "0"

                            requestAnimationFrame(() => {
                                botonAsignar.style.transform = "scale(1)"
                                botonAsignar.style.opacity = "1"
                            })
                        }, 150)
                    }
                } else {
                    modoFamiliarInput.value = "crear"
                    if (formCrear) formCrear.appendChild(modoFamiliarInput)

                    // Animación suave para ocultar asignar y mostrar crear
                    if (divCrear && divAsignar) {
                        divAsignar.style.opacity = "0"
                        divAsignar.style.transform = "translateY(-20px) scale(0.95)"

                        setTimeout(() => {
                            divAsignar.classList.add("hidden")
                            divCrear.classList.remove("hidden")
                            divCrear.style.opacity = "0"
                            divCrear.style.transform = "translateY(20px) scale(0.95)"

                            requestAnimationFrame(() => {
                                divCrear.style.opacity = "1"
                                divCrear.style.transform = "translateY(0) scale(1)"
                            })
                        }, 200)
                    }

                    // Cambiar botones con animación
                    if (botonAsignar && botonCrearOAsignar) {
                        botonAsignar.style.transform = "scale(0.9)"
                        botonAsignar.style.opacity = "0"

                        setTimeout(() => {
                            botonAsignar.classList.add("hidden")
                            botonAsignar.classList.remove("inline-flex")
                            botonCrearOAsignar.classList.remove("hidden")
                            botonCrearOAsignar.classList.add("inline-flex")

                            botonCrearOAsignar.style.transform = "scale(0.9)"
                            botonCrearOAsignar.style.opacity = "0"

                            requestAnimationFrame(() => {
                                botonCrearOAsignar.style.transform = "scale(1)"
                                botonCrearOAsignar.style.opacity = "1"
                            })
                        }, 150)
                    }
                }
            }

            // Event listeners
            if (crear) crear.addEventListener("change", toggleForm)
            if (asignar) asignar.addEventListener("change", toggleForm)

            // Inicializar
            toggleForm()
        })
    </script>
@endsection
