@extends('base.administrativo.blank')

@section('titulo')
    Períodos Académicos
@endsection

@section('just-after-html')
    <div class="delete-modal hidden">
        @include('layout.modals.modal-01', [
            'caution_message' => '¿Estás seguro?',
            'action' => 'Estás eliminando el Período Académico',
            'columns' => [ 'Nombre', 'Estado'],
            'rows' => ['nombre', 'estado'],
            'last_warning_message' => 'Borrar esto afectará a todas las matrículas vinculadas a este período',
            'confirm_button' => 'Sí, bórralo',
            'cancel_button' => 'Cancelar',
            'is_form' => true,
            'data_input_name' => 'id'
        ])
    </div>
@endsection

@section('contenido')
    @if(isset($data['created']))
        @include('layout.alerts.animated.timed-alert',[
            'message' => 'El Período Académico ha sido registrado exitosamente.',
            'route' => 'layout.alerts.success' 
        ])
    @endif

    @if(isset($data['edited']))
        @include('layout.alerts.animated.timed-alert',[
            'message' => 'El Período Académico ha sido editado exitosamente.',
            'route' => 'layout.alerts.orange-success' 
        ])
    @endif

    @if(isset($data['abort']))
        @include('layout.alerts.animated.timed-alert',[
            'message' => 'La acción sobre el período académico ha sido cancelada.',
            'route' => 'layout.alerts.info' 
        ])
    @endif

    @if(isset($data['deleted']))
        @include('layout.alerts.animated.timed-alert',[
            'message' => 'El período académico ha sido eliminado exitosamente.',
            'route' => 'layout.alerts.red-success' 
        ])
    @endif

    @if(session('success'))
        @include('layout.alerts.animated.timed-alert',[
            'message' => session('success'),
            'route' => 'layout.alerts.success' 
        ])
    @endif

    @if(session('error'))
        @include('layout.alerts.animated.timed-alert',[
            'message' => session('error'),
            'route' => 'layout.alerts.red-success' 
        ])
    @endif

    @include('layout.tables.table-01', $data)
@endsection

@section('custom-js')
    <script src="{{ asset('js/tables.js') }}"></script>
    <script src="{{ asset('js/delete-button-modal.js') }}"></script>
@endsection