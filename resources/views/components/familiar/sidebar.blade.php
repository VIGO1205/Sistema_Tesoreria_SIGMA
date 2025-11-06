@extends('layout.sidebar')

@section('opciones')

    {{-- Gestión Académica --}}
    @can('access-resource', 'datos')
        @include('components.para-sidebar.dropdown-button', [
            'name' => 'Datos personales',
            'items' => [
                'Datos del Alumno',
            ],
            'links' => [
                'Datos del Alumno' => 'familiar_dato_view',
            ],
            'icon' => 'persona'
        ])
    @endcan

    @can('access-resource', 'matriculas')
        @include('components.para-sidebar.dropdown-button', [
            'name' => 'Matrículas',
            'items' => [
                'Ver matrículas del Alumno',
            ],
            'links' => [
                'Ver matrículas del Alumno' => 'familiar_matricula_view',
            ],
            'icon' => 'birrete'
        ])
    @endcan

    @can('access-resource', 'pagos')
        @include('components.para-sidebar.dropdown-button', [
            'name' => 'Pagos',
            'items' => [
                'Ver pagos anteriores',
                'Ver deudas',
            ],
            'links' => [
                'Ver pagos anteriores' => 'familiar_pago_view_pagos',
                'Ver deudas' => 'familiar_pago_view_deudas',
            ],
            'icon' => 'monedas'
        ])
    @endcan
@endsection