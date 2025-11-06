<!-- Diseño elegante mejorado para tabla con paginación y controles -->
<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-6 pb-4 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
  <div class="flex flex-col gap-4 mb-4 sm:flex-row sm:items-center sm:justify-between">
    <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">
      {{ $titulo }}
    </h3>

    <div class="flex items-center gap-3">
      <button class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
        <svg class="stroke-current fill-white dark:fill-gray-800" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M2.29 5.904h15.417M17.707 14.096H2.291M12.083 3.333a2.571 2.571 0 110 5.143 2.571 2.571 0 010-5.143zM7.917 11.525a2.571 2.571 0 100 5.143 2.571 2.571 0 000-5.143z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Filtros
      </button>

      <button class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
        Ver todo
      </button>

      @can('manage-resource', [$resource, 'create'])
        <a href="{{ route($create) }}" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 text-white px-4 py-2 text-sm font-semibold shadow hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400">
          + Nuevo
        </a>
      @endcan
    </div>
  </div>

  <div class="w-full overflow-x-auto text-sm">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-4">
      <div class="flex items-center gap-3">
        <span class="text-gray-600 dark:text-gray-300">Viendo</span>
        <div class="relative">
          <select class="h-9 rounded-md border border-gray-300 bg-white px-3 pr-8 text-sm text-gray-800 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            <option value="10" @if ($showing == 10) selected @endif>10</option>
            <option value="8" @if ($showing == 8) selected @endif>8</option>
            <option value="5" @if ($showing == 5) selected @endif>5</option>
          </select>
          <span class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2">
            <svg class="text-gray-500 dark:text-gray-400" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3.833 5.917L8 10.083l4.167-4.166" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </span>
        </div>
        <span class="text-gray-600 dark:text-gray-300">entradas</span>
      </div>

      <div class="relative w-full md:max-w-xs">
        <span class="absolute top-1/2 left-3 -translate-y-1/2">
          <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.042 9.374a6.333 6.333 0 1112.667 0 6.333 6.333 0 01-12.667 0zm6.333-7.833a7.833 7.833 0 100 15.667A7.833 7.833 0 009.375 1.54zm8.863 16.697a.667.667 0 01-.943-.943l-2.822-2.82a7.805 7.805 0 001.177-1.077l2.824 2.823a.667.667 0 01-.236.943z"/></svg>
        </span>
        <input type="text" placeholder="Buscar..." class="w-full rounded-md border border-gray-300 pl-10 pr-4 py-2 text-sm text-gray-800 placeholder-gray-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:placeholder-gray-400">
      </div>
    </div>

    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left">
      <thead class="bg-gray-100 dark:bg-gray-800">
        <tr>
          @foreach ($columnas as $columna)
            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300 align-middle">
              {{ $columna }}
            </th>
          @endforeach
          @if (Gate::allows('manage-resource', [$resource, 'edit']) || Gate::allows('manage-resource', [$resource, 'delete']))
            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300 align-middle">
              Acción
            </th>
          @endif
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-gray-800 align-middle">
        @foreach($filas as $fila)
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
            @for($i = 0; $i < count($columnas); $i++)
              <td class="px-4 py-3 text-gray-700 dark:text-gray-300 align-middle">
                {{ $fila[$i] }}
              </td>
            @endfor
            @if (Gate::allows('manage-resource', [$resource, 'edit']) || Gate::allows('manage-resource', [$resource, 'delete']))
              <td class="px-4 py-3 align-middle">
                <div class="flex gap-3">
                  @can('manage-resource', [$resource, 'edit'])
                    <a href="{{ route($edit, [$fila[0]]) }}" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6-6m-2 2l-6 6v2h2l6-6" />
                      </svg>
                      Editar
                    </a>
                  @endcan
                  @can('manage-resource', [$resource, 'delete'])
                    <button data-id="{{$fila[0]}}" class="inline-flex items-center gap-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                      Eliminar
                    </button>
                  @endcan
                </div>
              </td>
            @endif
          </tr>
        @endforeach
      </tbody>
    </table>

    @if (isset($paginaActual))
      <div class="flex justify-center gap-6 p-4">
        @if ($paginaActual > 1)
          <button id="{{ $paginaActual - 1 }}" class="px-4 py-2 text-sm rounded-md border bg-white text-gray-700 hover:bg-gray-100 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
            ← Anterior
          </button>
        @endif

        <span class="text-gray-700 dark:text-gray-300">Página {{ $paginaActual }} de {{ $totalPaginas }}</span>

        @if ($paginaActual < $totalPaginas)
          <button id="{{ $paginaActual + 1 }}" class="px-4 py-2 text-sm rounded-md border bg-white text-gray-700 hover:bg-gray-100 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
            Siguiente →
          </button>
        @endif
      </div>
    @endif
  </div>
</div>
