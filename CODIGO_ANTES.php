@extends('base.administrativo.blank')

@section('titulo')
    Nueva Asignación Familiar-Alumno
@endsection

@section('contenido')
    <div class="p-8 m-4 bg-gray-100 dark:bg-white/[0.03] rounded-2xl">
        <!-- Header -->
        <div class="flex pb-6 justify-between items-center border-b border-gray-200 dark:border-gray-700">
            <div>
                <h2 class="text-2xl font-bold dark:text-gray-200 text-gray-800">Nueva Asignación Familiar-Alumno</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Vincular un familiar con un alumno</p>
            </div>
            <div class="flex gap-3">
                <button type="button" id="btnGuardar"
                    class="cursor-pointer inline-flex items-center gap-2 rounded-lg border border-blue-300 bg-blue-500 px-6 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700">
                    Guardar Asignación
                </button>

                <a href="{{ $data['return'] }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                    Cancelar
                </a>
            </div>
        </div>

        <!-- Mensajes de Error -->
        @if ($errors->any())
            <div class="mt-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error:</strong>
                <ul class="mt-1 ml-4 list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario -->
        <form method="POST" id="formAsignarFamiliar" action="{{ route('composicion_familiar_store') }}" class="mt-8">
            @method('POST')
            @csrf
            <input type="hidden" name="estado" value="1">

            <div class="mb-8">
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
                        'value' => old('id_alumno')
                    ])

                    <!-- Selección de Familiar -->
                    @include('components.forms.combo', [
                        'label' => 'Seleccionar Familiar/Padre',
                        'name' => 'id_familiar',
                        'options' => $data['familiares'],
                        'options_attributes' => ['id', 'nombre'],
                        'error' => $errors->first('id_familiar') ?? false,
                        'value' => old('id_familiar')
                    ])

                    <!-- Selección de Parentesco -->
                    @include('components.forms.combo', [
                        'label' => 'Parentesco',
                        'name' => 'parentesco',
                        'options' => $data['parentescos'],
                        'options_attributes' => ['id', 'descripcion'],
                        'error' => $errors->first('parentesco') ?? false,
                        'value' => old('parentesco')
                    ])
                </div>
            </div>
        </form>
    </div>

    <!-- Modal de Confirmación -->
    <div id="modalConfirmacion" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50" style="display: none;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900">
                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-5">Confirmar Asignación</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-300 mb-4">
                        ¿Está seguro que desea realizar esta asignación entre el familiar y el alumno seleccionados?
                    </p>
                    <div id="infoAsignacion" class="mt-4 text-left bg-gray-50 dark:bg-gray-700 p-3 rounded">
                        <p class="text-sm text-gray-700 dark:text-gray-300"><strong>Alumno:</strong> <span id="nombreAlumno"></span></p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><strong>Familiar:</strong> <span id="nombreFamiliar"></span></p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-1"><strong>Parentesco:</strong> <span id="nombreParentesco"></span></p>
                    </div>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="btnConfirmarSi"
                            class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        Sí, asignar
                    </button>
                    <button id="btnConfirmarNo"
                            class="mt-3 px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formAsignarFamiliar');
        const modal = document.getElementById('modalConfirmacion');
        const btnGuardar = document.getElementById('btnGuardar');
        const btnConfirmarSi = document.getElementById('btnConfirmarSi');
        const btnConfirmarNo = document.getElementById('btnConfirmarNo');
        const selectAlumno = document.getElementById('id_alumno');
        const selectFamiliar = document.getElementById('id_familiar');
        const selectParentesco = document.getElementById('parentesco');

        // Prevenir envío directo del formulario
        btnGuardar.addEventListener('click', function(e) {
            e.preventDefault();

            // Validar que todos los campos estén llenos
            if (!selectAlumno.value || !selectFamiliar.value || !selectParentesco.value) {
                alert('Por favor, complete todos los campos requeridos.');
                return;
            }

            // Mostrar información en el modal
            document.getElementById('nombreAlumno').textContent = selectAlumno.options[selectAlumno.selectedIndex].text;
            document.getElementById('nombreFamiliar').textContent = selectFamiliar.options[selectFamiliar.selectedIndex].text;
            document.getElementById('nombreParentesco').textContent = selectParentesco.options[selectParentesco.selectedIndex].text;

            // Mostrar modal
            modal.style.display = 'block';
        });

        // Confirmar asignación
        btnConfirmarSi.addEventListener('click', function() {
            modal.style.display = 'none';
            form.submit();
        });

        // Cancelar modal
        btnConfirmarNo.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // Cerrar modal al hacer clic fuera
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
    </script>

@endsection
