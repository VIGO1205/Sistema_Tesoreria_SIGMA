@extends('base.administrativo.blank')

@section('titulo')
    Nueva Asignación Familiar-Alumno
@endsection

@section('contenido')
    <div class="p-8 m-4 bg-gray-100 dark:bg-white/[0.03] rounded-2xl">
        <!-- Mensajes de Error -->
        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error:</strong>
                <ul class="mt-1 ml-4 list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">¡Éxito!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Formulario -->
        <form method="POST" id="formAsignarFamiliar" action="{{ route('composicion_familiar_store') }}">
            @csrf
            <input type="hidden" name="estado" value="1">

            <!-- Header (ahora dentro del form para el botón submit) -->
            <div class="flex pb-6 justify-between items-center border-b border-gray-200 dark:border-gray-700">
                <div>
                    <h2 class="text-2xl font-bold dark:text-gray-200 text-gray-800">Nueva Asignación Familiar-Alumno</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Vincular un familiar con un alumno</p>
                </div>
                <div class="flex gap-3">
                    <button type="submit"
                        class="cursor-pointer inline-flex items-center gap-2 rounded-lg border border-blue-300 bg-blue-500 px-6 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700">
                        Guardar Asignación
                    </button>

                    <a href="{{ $data['return'] }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                        Cancelar
                    </a>
                </div>
            </div>

            <div class="mb-8 pt-6">  <!-- Padding top para separar del header -->
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Datos de la Asignación
                </h3>

                <div class="space-y-6">
                    <!-- Selección de Alumno -->
                    @include('components.forms.combo', [
                        'label' => 'Seleccionar Alumno',
                        'name' => 'id_alumno',
                        'options' => $data['alumnos'],
                        'options_attributes' => ['id', 'nombre'],
                        'error' => $errors->first('id_alumno') ?? false,
                        'value' => old('id_alumno'),
                        'required' => true  // Si el componente lo soporta, para validación HTML
                    ])

                    <!-- Selección de Familiar -->
                    @include('components.forms.combo', [
                        'label' => 'Seleccionar Familiar/Padre',
                        'name' => 'id_familiar',
                        'options' => $data['familiares'],
                        'options_attributes' => ['id', 'nombre'],
                        'error' => $errors->first('id_familiar') ?? false,
                        'value' => old('id_familiar'),
                        'required' => true
                    ])

                    <!-- Selección de Parentesco -->
                    @include('components.forms.combo', [
                        'label' => 'Parentesco',
                        'name' => 'parentesco',
                        'options' => $data['parentescos'],
                        'options_attributes' => ['id', 'descripcion'],
                        'error' => $errors->first('parentesco') ?? false,
                        'value' => old('parentesco'),
                        'required' => true
                    ])
                </div>
            </div>
        </form>
    </div>
@endsection
