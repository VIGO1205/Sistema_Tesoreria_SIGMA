@extends('base.administrativo.blank')

@section('titulo')
    Agregar Docente - {{ $data['departamento']->nombre }}
@endsection

@section('contenido')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Agregar Docente al Departamento: {{ $data['departamento']->nombre }}</h2>

        <form action="{{ route('departamento_academico_guardar_docente', ['id' => $data['departamento']->id_departamento]) }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="id_personal" class="block text-sm font-medium text-gray-700 mb-2">
                    Seleccionar Docente
                </label>
                <select name="id_personal" id="id_personal" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Seleccione un docente --</option>
                    @foreach($data['docentes_disponibles'] as $docente)
                        <option value="{{ $docente->id_personal }}">
                            {{ $docente->dni }} - {{ $docente->primer_nombre }} {{ $docente->apellido_paterno }} {{ $docente->apellido_materno }}
                        </option>
                    @endforeach
                </select>
                @error('id_personal')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            @if($data['docentes_disponibles']->isEmpty())
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                    No hay docentes disponibles para agregar. Todos los docentes ya están asignados a un departamento.
                </div>
            @endif

            <div class="flex gap-4">
                <a href="{{ $data['return'] }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                    Cancelar
                </a>
                @if($data['docentes_disponibles']->isNotEmpty())
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                        Agregar Docente
                    </button>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection