@extends('base.administrativo.blank')

@section('titulo')
  Editar Deuda
@endsection

@section('contenido')
  <div class="p-8 m-4 dark:bg-white/[0.03] rounded-2xl">
    <div class="flex pb-4 justify-between items-center">
      <h2 class="text-lg dark:text-gray-200 text-gray-800">Estás editando la Deuda con ID {{$data['id']}}</h2>

      <div class="flex gap-4">
        <input form="form" target="" type="submit" form=""
          class="cursor-pointer inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-gray-200 px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-300 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-white/[0.03] dark:hover:text-gray-200"
          value="Guardar"
        >

        <a
          href="{{ $data['return'] }}"
          class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-gray-200 px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-300 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-white/[0.03] dark:hover:text-gray-200"
        >
          Cancelar
        </a>
      </div>
    </div>

    <form method="POST" id="form" class="flex flex-col gap-4" action="">
      @method('PATCH')
      @csrf


      @include('components.forms.date-picker', [
        'label' => 'Fecha Limite',
        'error' => $errors->first(Str::snake('Fecha Limite')) ?? false,
        'value' => old(Str::snake('Fecha Limite')) ?? $data['default'][Str::snake('Fecha Limite')],
      ])

      @include('components.forms.string', [
        'label' => 'Monto total',
        'error' => $errors->first(Str::snake('Monto total')) ?? false,
        'value' => old(Str::snake('Monto Total')) ?? $data['default'][Str::snake('Monto Total')],
      ])

      @include('components.forms.string-ineditable', [
        'label' => 'Periodo',
        'name' => 'periodo',
        'error' => $errors->first(Str::snake('Periodo')) ?? false,
        'value' => old(Str::snake('Periodo')) ?? $data['default'][Str::snake('Periodo')],
        'readonly' => true
        ])

      @include('components.forms.text-area', [
        'label' => 'Observacion',
        'error' => $errors->first(Str::snake('Observacion')) ?? false,
        'value' => old(Str::snake('observacion')) ?? $data['default'][Str::snake('observacion')],
      ])



    </form>
  </div>
@endsection

@section('custom-js')
  <!-- Si es necesario agregar algún JS adicional -->
@endsection
