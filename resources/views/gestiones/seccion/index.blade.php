@extends('base.administrativo.blank')

@section('titulo')
  {{ $data['titulo'] }}
@endsection

{{-- Modal de borrado --}}

@section('just-after-html') 
    <div class="delete-modal hidden">
        @include('layout.modals.modal-01-2param', [
            'caution_message' => '¿Estás seguro?',
            'action' => 'Estás eliminando la Seccion:' ,
            
            'columns' => [
                'Nivel Educativo',
                'Seccion',
                'Grado'
            ],
            
            'rows' => [
                'null1',
                'null2',
                'null3'
            ],
            
            'last_warning_message' => 'Borrar esto afectará a todo lo que esté vinculado a esta Seccion',
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
        'message' => 'La Seccion ha sido registrado exitosamente.',
        'route' => 'layout.alerts.success' 
        ])
    @endif

    @if(isset($data['edited']))
        @include('layout.alerts.animated.timed-alert',[
        'message' => 'La Seccion ha sido editado exitosamente.',
        'route' => 'layout.alerts.orange-success' 
        ])
    @endif

    @if(isset($data['abort']))
        @include('layout.alerts.animated.timed-alert',[
        'message' => 'La acción sobre la sección ha sido cancelada.',
        'route' => 'layout.alerts.info' 
        ])
    @endif

    @if(isset($data['deleted']))
        @include('layout.alerts.animated.timed-alert',[
        'message' => 'La Seccion ha sido eliminado exitosamente.',
        'route' => 'layout.alerts.red-success' 
        ])
    @endif

    @include('layout.tables.table-view_details-2param', $data) {{-- -esta linea es todo xd --}}

@endsection

@section('custom-js')
  <script src="{{ asset('js/tables.js') }}"></script>
  <script src="{{ asset('js/delete-button-modal-2param.js') }}"></script>
@endsection
