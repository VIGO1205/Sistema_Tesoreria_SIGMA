@extends('base.administrativo.blank')

@section('titulo')
    Docente | Edición
@endsection

@section('extracss')
@endsection

@section('contenido')
    <div class="p-8 m-4 bg-gray-100 dark:bg-white/[0.03] rounded-2xl">
        <!-- Header -->
        <div class="flex pb-6 justify-between items-center border-b border-gray-200 dark:border-gray-700">
            <div>
                <h2 class="text-2xl font-bold dark:text-gray-200 text-gray-800">Editar Docente</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">ID: {{ $data['id'] }}</p>
            </div>
            <div class="flex gap-3">
                <input form="form" type="submit"
                    class="cursor-pointer inline-flex items-center gap-2 rounded-lg border border-green-300 bg-green-500 px-6 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:border-green-600 dark:bg-green-600 dark:hover:bg-green-700"
                    value="Guardar Cambios"
                >
                <a href="{{ $data['return'] }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                >
                    Cancelar
                </a>
            </div>
        </div>

        <form method="POST" id="form" action="" class="mt-8">
            @method('PATCH')
            @csrf

            <!-- Información Básica -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-4 0V4a2 2 0 014 0v2"></path>
                    </svg>
                    Información Básica
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @include('components.forms.string-ineditable', [
                        'label' => 'ID',
                        'name' => 'id',
                        'error' => $errors->first('id') ?? false,
                        'value' => $data['id'],
                        'readonly' => true
                    ])

                    @include('components.forms.string', [
                        'label' => 'DNI',
                        'name' => Str::snake('dni'),
                        'error' => $errors->first(Str::snake('dni')) ?? false,
                        'value' => old(Str::snake('dni')) ?? $data['default']['dni']
                    ])

                    @include('components.forms.string', [
                        'label' => 'Código de Personal',
                        'name' => Str::snake('Codigo de Personal'),
                        'error' => $errors->first(Str::snake('Codigo de Personal')) ?? false,
                        'value' => old(Str::snake('Codigo de Personal')) ?? $data['default'][Str::snake('Codigo de Personal')]
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @include('components.forms.string', [
                        'label' => 'Apellido Paterno',
                        'name' => Str::snake('Apellido Paterno'),
                        'error' => $errors->first(Str::snake('Apellido Paterno')) ?? false,
                        'value' => old(Str::snake('Apellido Paterno')) ?? $data['default'][Str::snake('Apellido Paterno')]
                    ])

                    @include('components.forms.string', [
                        'label' => 'Apellido Materno',
                        'name' => Str::snake('Apellido Materno'),
                        'error' => $errors->first(Str::snake('Apellido Materno')) ?? false,
                        'value' => old(Str::snake('Apellido Materno')) ?? $data['default'][Str::snake('Apellido Materno')]
                    ])

                    @include('components.forms.string', [
                        'label' => 'Primer Nombre',
                        'name' => Str::snake('Primer Nombre'),
                        'error' => $errors->first(Str::snake('Primer Nombre')) ?? false,
                        'value' => old(Str::snake('Primer Nombre')) ?? $data['default'][Str::snake('Primer Nombre')]
                    ])

                    @include('components.forms.string', [
                        'label' => 'Otros Nombres',
                        'name' => Str::snake('Otros Nombres'),
                        'error' => $errors->first(Str::snake('Otros Nombres')) ?? false,
                        'value' => old(Str::snake('Otros Nombres')) ?? $data['default'][Str::snake('Otros Nombres')]
                    ])
                </div>
            </div>

            <!-- Información de Contacto -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Información de Contacto
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        @include('components.forms.string', [
                            'label' => 'Dirección',
                            'name' => Str::snake('Direccion'),
                            'error' => $errors->first(Str::snake('Direccion')) ?? false,
                            'value' => old(Str::snake('Direccion')) ?? $data['default'][Str::snake('Direccion')]
                        ])
                    </div>

                    @include('components.forms.string', [
                        'label' => 'Teléfono',
                        'name' => Str::snake('Telefono'),
                        'error' => $errors->first(Str::snake('Telefono')) ?? false,
                        'value' => old(Str::snake('Telefono')) ?? $data['default'][Str::snake('Telefono')]
                    ])

                    @include('components.forms.combo', [
                        'label' => 'Estado Civil',
                        'name' => Str::snake('Estado Civil'),
                        'error' => $errors->first(Str::snake('Estado Civil')) ?? false,
                        'value' => old(Str::snake('Estado Civil'), $data['default'][Str::snake('Estado Civil')]) ?? $data['default'][Str::snake('Estado Civil')],
                        'options' => $data['estadosCiviles'],
                        'options_attributes' => ['id', 'descripcion']
                    ])
                </div>
            </div>

            <!-- Información Laboral -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                    </svg>
                    Información Laboral
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @include('components.forms.string', [
                        'label' => 'Seguro Social',
                        'name' => Str::snake('Seguro Social'),
                        'error' => $errors->first(Str::snake('Seguro Social')) ?? false,
                        'value' => old(Str::snake('Seguro Social')) ?? $data['default'][Str::snake('Seguro Social')]
                    ])

                    @include('components.forms.date', [
                        'label' => 'Fecha Ingreso',
                        'name' => Str::snake('Fecha Ingreso'),
                        'error' => $errors->first(Str::snake('Fecha Ingreso')) ?? false,
                        'value' => old(Str::snake('Fecha Ingreso')) ?? $data['default'][Str::snake('Fecha Ingreso')]
                    ])

                    @include('components.forms.combo', [
                        'label' => 'Departamento',
                        'name' => Str::snake('Departamento'),
                        'error' => $errors->first(Str::snake('Departamento')) ?? false,
                        'value' => old(Str::snake('Departamento'), $data['default']['departamento']) ?? $data['default']['departamento'],
                        'options' => $data['departamentos'],
                        'options_attributes' => ['id_departamento', 'nombre']
                    ])

                    @include('components.forms.combo', [
                        'label' => 'Categoría',
                        'name' => Str::snake('Categoria'),
                        'error' => $errors->first(Str::snake('Categoria')) ?? false,
                        'value' => old(Str::snake('Categoria'), $data['default']['categoria']) ?? $data['default']['categoria'],
                        'options' => $data['categorias'],
                        'options_attributes' => ['id', 'descripcion']
                    ])
                </div>
            </div>

            <!-- Botones de acción en la parte inferior -->
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ $data['return'] }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </a>
                <input form="form" type="submit"
                    class="cursor-pointer inline-flex items-center gap-2 rounded-lg border border-green-300 bg-green-500 px-6 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:border-green-600 dark:bg-green-600 dark:hover:bg-green-700"
                    value="💾 Guardar Cambios"
                >
            </div>
        </form>
    </div>
@endsection

@section('custom-js')
    <script src="{{ asset('js/tables.js') }}"></script>
@endsection