@extends('base.administrativo.blank')

@section('titulo')
    Crear Período Académico
@endsection

@section('contenido')
    <div class="p-8 m-4 bg-gray-100 dark:bg-white/[0.03] rounded-2xl">
        <!-- Header -->
        <div class="flex pb-6 justify-between items-center border-b border-gray-200 dark:border-gray-700">
            <div>
                <h2 class="text-2xl font-bold dark:text-gray-200 text-gray-800">Nuevo Período Académico</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Registra un nuevo período académico en el sistema</p>
            </div>
            <div class="flex gap-3">
                <input form="form" type="submit"
                    class="cursor-pointer inline-flex items-center gap-2 rounded-lg border border-green-300 bg-green-500 px-6 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:border-green-600 dark:bg-green-600 dark:hover:bg-green-700"
                    value="Guardar"
                >
                <a href="{{ $data['return'] }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                >
                    Cancelar
                </a>
            </div>
        </div>

        <form method="POST" id="form" action="" class="mt-8">
            @method('PUT')
            @csrf

            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-200 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Información del Período
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @include('components.forms.string', [
                        'label' => 'Nombre del Período',
                        'name' => 'nombre',
                        'error' => $errors->first('nombre') ?? false,
                        'value' => old('nombre'),
                        'required' => true,
                        'placeholder' => 'Ej: 2026, 2026-I, etc.'
                    ])

                    @php
                        $estados = \App\Models\EstadoPeriodoAcademico::all();
                    @endphp
                    
                    @include('components.forms.select', [
                        'label' => 'Estado del Período',
                        'name' => 'estado',
                        'error' => $errors->first('estado') ?? false,
                        'value' => old('estado', \App\Models\EstadoPeriodoAcademico::PROGRAMADO),
                        'required' => true,
                        'options' => $estados->pluck('nombre'),
                        'option_values' => $estados->map(function($estado){ return $estado->getKey(); })->toArray()
                    ])
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
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
                    value="💾 Guardar Período"
                >
            </div>
        </form>
    </div>
@endsection

@section('custom-js')
    <script src="{{ asset('js/tables.js') }}"></script>
@endsection