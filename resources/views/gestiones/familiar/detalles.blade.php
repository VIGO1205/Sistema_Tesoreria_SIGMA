@extends('base.administrativo.blank')

@section('titulo')
    Detalles del Familiar #{{ $familiar->idFamiliar }}
@endsection

@section('contenido')
    <h2 class="text-lg mb-4 dark:text-amber-50 text-black ">Detalles del Familiar #{{ $familiar->idFamiliar }}</h2>
    <p class="dark:text-amber-50 text-black"><strong>DNI:</strong> {{ $familiar->dni }}</p>
    <p class="dark:text-amber-50 text-black"><strong>Nombre:</strong> {{ $familiar->primer_nombre }} {{ $familiar->otros_nombres }}</p>
    <p class="dark:text-amber-50 text-black"><strong>Apellidos:</strong> {{ $familiar->apellido_paterno }} {{ $familiar->apellido_materno }}</p>
    <p class="dark:text-amber-50 text-black"><strong>Contacto:</strong> {{ $familiar->numero_contacto }}</p>
    <p class="dark:text-amber-50 text-black"><strong>Correo electrónico:</strong> {{ $familiar->correo_electronico }}</p>
    <hr class="my-4">

    @php
        $titulo = "Alumnos asociados";
        $columnas = ['ID', 'DNI', 'Nombres', 'Apellidos',  'Parentesco'];
        $filas = [];
        foreach($familiar->alumnos as $alumno) {
            $filas[] = [
                $alumno->id_alumno,
                $alumno->dni,
                $alumno->primer_nombre . ' ' . $alumno->otros_nombres,
                $alumno->apellido_paterno . ' ' . $alumno->apellido_materno,
                $alumno->pivot->parentesco
            ];
        }
        $resource = 'alumnos';
        $create = null;
        $showing = 10;
        $paginaActual = 1;
        $totalPaginas = 1;
    @endphp

@include('layout.tables.table-detalles', compact('titulo', 'columnas', 'filas', 'resource', 'create', 'showing', 'paginaActual', 'totalPaginas'))
@endsection