@extends('base.administrativo.blank')

@section('titulo')
  {{ $data['titulo'] }}
@endsection

@section('just-after-html')
  <div class="delete-modal hidden">
    @include('layout.modals.modal-01', [
      'caution_message' => '¿Estás seguro?',
      'action' => 'Estás eliminando la Orden de Pago',
      'columns' => [
        'Código',
        'Alumno',
        'Grado/Sección',
        'Monto Total',
        'Fecha Vencimiento',
        'Estado',
        '',
      ],
      'rows' => [
        'codigo',
        'alumno',
        'grado-seccion',
        'monto',
        'vencimiento',
        'estado',
        'btn'
      ],
      'last_warning_message' => 'Borrar esto afectará a todo lo que esté vinculado a esta Orden de Pago',
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
          'message' => 'La orden de pago ha sido generada exitosamente.',
          'route' => 'layout.alerts.success' 
        ])
      @endif

      @if(isset($data['edited']))
        @include('layout.alerts.animated.timed-alert',[
          'message' => 'La orden de pago ha sido actualizada exitosamente.',
          'route' => 'layout.alerts.orange-success' 
        ])
      @endif

      @if(isset($data['abort']))
        @include('layout.alerts.animated.timed-alert',[
          'message' => 'La acción sobre la orden de pago ha sido cancelada.',
          'route' => 'layout.alerts.info' 
        ])
      @endif

      @if(isset($data['deleted']))
        @include('layout.alerts.animated.timed-alert',[
          'message' => 'La orden de pago ha sido eliminada exitosamente.',
          'route' => 'layout.alerts.red-success' 
        ])
      @endif

      @include('layout.tables.table-ordenes-pago', $data)    
@endsection

@section('custom-js')
  <script src="{{ asset('js/tables.js') }}"></script>
  <script src="{{ asset('js/delete-button-modal.js') }}"></script>
@endsection
