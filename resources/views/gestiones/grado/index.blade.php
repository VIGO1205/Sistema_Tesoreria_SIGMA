@extends('base.administrativo.blank')

@section('titulo')
  {{ $data['titulo'] }}
@endsection

{{-- Modal de borrado --}}

@section('just-after-html') 
    <div class="delete-modal hidden">
        @include('layout.modals.modal-01', [
            'caution_message' => '¿Estás seguro?',
            'action' => 'Estás eliminando el Grado:' ,
            
            'columns' => [
                'Grado',
                'Nivel Educativo'
            ],
            
            'rows' => [
                'nombre_grado',
                'nivelEducativo->descripcion',
            ],
            
            'last_warning_message' => 'Borrar esto afectará a todo lo que esté vinculado a este Grado',
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
        'message' => 'El Grado ha sido registrado exitosamente.',
        'route' => 'layout.alerts.success' 
        ])
    @endif

    @if(isset($data['edited']))
        @include('layout.alerts.animated.timed-alert',[
        'message' => 'El Grado ha sido editado exitosamente.',
        'route' => 'layout.alerts.orange-success' 
        ])
    @endif

    @if(isset($data['abort']))
        @include('layout.alerts.animated.timed-alert',[
        'message' => 'La acción sobre el grado ha sido cancelada.',
        'route' => 'layout.alerts.info' 
        ])
    @endif

    @if(isset($data['deleted']))
        @include('layout.alerts.animated.timed-alert',[
        'message' => 'El grado ha sido eliminado exitosamente.',
        'route' => 'layout.alerts.red-success' 
        ])
    @endif

    @include('layout.tables.table-view_details', $data) {{-- -esta linea es todo xd --}}

@endsection

@section('custom-js')
  <script src="{{ asset('js/tables.js') }}"></script>
  <script src="{{ asset('js/delete-button-modal.js') }}"></script>
@endsection
