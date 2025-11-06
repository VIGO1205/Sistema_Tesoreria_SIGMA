@extends('base.administrativo.blank')

@section('titulo')
  {{ $data['titulo'] }}
@endsection

@section('contenido')
  <form class="flex flex-col gap-4" action="">

  @include('components.forms.string', ['label' => 'Nivel'])
  @include('components.forms.string', ['label' => 'Nivel con Placeholder',
    'placeholder' => 'placeholder1'
  ])

  @include('components.forms.select', [
    'label' => 'Seleccionar',
    'options' => ['OpcionA', 'OpcionB', 'OpcionC']
  ])

  @include('components.forms.date-picker', ['label' => 'fecha'])
  
  @include('components.forms.text-area', ['label' => 'textarea'])

  @include('components.forms.text-area', ['label' => 'Textarea con Placeholder',
    'placeholder' => 'placeholder1'
  ])

  </form>
@endsection

@section('custom-js')
  <script src="{{ asset('js/tables.js') }}"></script>
@endsection