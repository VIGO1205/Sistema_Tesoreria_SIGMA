@extends('base.administrativo.blank')

@section('titulo')
    Conceptos de Acción
@endsection

@section('contenido')

@php

use App\Models\ConceptoAccion;
$filas = [];

$query = ConceptoAccion::where('estado', '=', '1')->paginate(10);

foreach ($query as $concepto) {
    array_push($filas, [
        $concepto->id_concepto_accion,
        $concepto->descripcion,
    ]);
}

$data = [
  'titulo' => 'Conceptos de Acción',
  'columnas' => [
    'ID',
    'Descripción'
  ],
  'filas' => $filas
]

@endphp

@include('layout.tables.table-01', $data)

@endsection