<div x-data="{ 
  showFilters: @js(request('applied_filters') && json_decode(request('applied_filters', '[]'), true) !== [])
}">
  <!-- Componente de filtros que se muestra/oculta -->
  <div x-show="showFilters" x-transition>
    @include('components.optional.filters', compact('columnas') + ['filterOptions' => $filterOptions ?? []])
    <br>
  </div>

  <div
    class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6"
  >
    <div
      class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between"
    >
      <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
          {{ $titulo }}
        </h3>
      </div>

      <div class="flex items-center gap-3">
        <button
          @click="showFilters = !showFilters"
          :class="showFilters ? 'bg-blue-50 border-blue-300 text-blue-700 dark:bg-blue-900/20 dark:border-blue-700 dark:text-blue-300' : 'bg-white border-gray-300 text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400'"
          class="inline-flex items-center gap-2 rounded-lg border px-4 py-2.5 text-theme-sm font-medium shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 transition-colors duration-200"
        >
          <svg
            class="stroke-current fill-white dark:fill-gray-800"
            width="20"
            height="20"
            viewBox="0 0 20 20"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M2.29004 5.90393H17.7067"
              stroke=""
              stroke-width="1.5"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
            <path
              d="M17.7075 14.0961H2.29085"
              stroke=""
              stroke-width="1.5"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
            <path
              d="M12.0826 3.33331C13.5024 3.33331 14.6534 4.48431 14.6534 5.90414C14.6534 7.32398 13.5024 8.47498 12.0826 8.47498C10.6627 8.47498 9.51172 7.32398 9.51172 5.90415C9.51172 4.48432 10.6627 3.33331 12.0826 3.33331Z"
              fill=""
              stroke=""
              stroke-width="1.5"
            />
            <path
              d="M7.91745 11.525C6.49762 11.525 5.34662 12.676 5.34662 14.0959C5.34661 15.5157 6.49762 16.6667 7.91745 16.6667C9.33728 16.6667 10.4883 15.5157 10.4883 14.0959C10.4883 12.676 9.33728 11.525 7.91745 11.525Z"
              fill=""
              stroke=""
              stroke-width="1.5"
            />
          </svg>

          <span x-text="showFilters ? 'Ocultar filtros' : 'Filtros'"></span>
        </button>

      <a
        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200"

        href="@if (isset($long) && $long) {{route($view)}} @else {{route($view)}} @endif"
      >
        @if(isset($long) && $long) Ver menos @else Ver más @endif
      </a>

    @can('manage-resource', [$resource, 'download'])
        <div x-data="{ showDownloadMenu: false }" class="relative">
          <button @click="showDownloadMenu = !showDownloadMenu"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200"
          >
            <!-- Download Icon SVG -->
            <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M12.5535 16.5061C12.4114 16.6615 12.2106 16.75 12 16.75C11.7894 16.75 11.5886 16.6615 11.4465 16.5061L7.44648 12.1311C7.16698 11.8254 7.18822 11.351 7.49392 11.0715C7.79963 10.792 8.27402 10.8132 8.55352 11.1189L11.25 14.0682V3C11.25 2.58579 11.5858 2.25 12 2.25C12.4142 2.25 12.75 2.58579 12.75 3V14.0682L15.4465 11.1189C15.726 10.8132 16.2004 10.792 16.5061 11.0715C16.8118 11.351 16.833 11.8254 16.5535 12.1311L12.5535 16.5061Z"/>
              <path d="M3.75 15C3.75 14.5858 3.41422 14.25 3 14.25C2.58579 14.25 2.25 14.5858 2.25 15V15.0549C2.24998 16.4225 2.24996 17.5248 2.36652 18.3918C2.48754 19.2919 2.74643 20.0497 3.34835 20.6516C3.95027 21.2536 4.70814 21.5125 5.60825 21.6335C6.47522 21.75 7.57754 21.75 8.94513 21.75H15.0549C16.4225 21.75 17.5248 21.75 18.3918 21.6335C19.2919 21.5125 20.0497 21.2536 20.6517 20.6516C21.2536 20.0497 21.5125 19.2919 21.6335 18.3918C21.75 17.5248 21.75 16.4225 21.75 15.0549V15C21.75 14.5858 21.4142 14.25 21 14.25C20.5858 14.25 20.25 14.5858 20.25 15C20.25 16.4354 20.2484 17.4365 20.1469 18.1919C20.0482 18.9257 19.8678 19.3142 19.591 19.591C19.3142 19.8678 18.9257 20.0482 18.1919 20.1469C17.4365 20.2484 16.4354 20.25 15 20.25H9C7.56459 20.25 6.56347 20.2484 5.80812 20.1469C5.07435 20.0482 4.68577 19.8678 4.40901 19.591C4.13225 19.3142 3.9518 18.9257 3.85315 18.1919C3.75159 17.4365 3.75 16.4354 3.75 15Z"/>
            </svg>
            Descargar
            <!-- Chevron Down Icon -->
            <svg class="h-4 w-4 fill-current transition-transform duration-200" :class="{ 'rotate-180': showDownloadMenu }" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
          </button>
          
          <div x-show="showDownloadMenu" 
               x-transition:enter="transition ease-out duration-100"
               x-transition:enter-start="transform opacity-0 scale-95"
               x-transition:enter-end="transform opacity-100 scale-100"
               x-transition:leave="transition ease-in duration-75"
               x-transition:leave-start="transform opacity-100 scale-100"
               x-transition:leave-end="transform opacity-0 scale-95"
               @click.away="showDownloadMenu = false"
               class="absolute right-0 z-50 mt-2 w-48 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 dark:bg-gray-800 dark:ring-gray-700">
            <div class="py-1">
              <button type="button" 
                      onclick="downloadExport('excel')"
                      class="group flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                <!-- Excel Icon -->
                <svg class="mr-3 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm0 2h12v10H4V5z"/>
                  <path d="M6 7h2v2H6V7zm0 4h2v2H6v-2zm4-4h2v2h-2V7zm0 4h2v2h-2v-2z"/>
                </svg>
                Descargar Excel
              </button>
              <button type="button" 
                      onclick="downloadExport('pdf')"
                      class="group flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                <!-- PDF Icon -->
                <svg class="mr-3 h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                </svg>
                Descargar PDF
              </button>
            </div>
          </div>
        </div>
    @endcan

    @can('manage-resource', [$resource, 'create'])
        <a href="{{ route($create) }}"
          class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-gray-200 px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-300 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-white/[0.03] dark:hover:text-gray-200"
        >
          Crear un nuevo registro
        </a>
    @endif
      </div>
    </div>
<div class="text-[0.9rem] space-y-4">
  <!-- Selector y buscador -->
  <div class="flex items-center justify-between flex-wrap gap-4 pb-4">
    <div class="flex items-center gap-3 flex-wrap">
      <span class="text-gray-500 dark:text-gray-300">Viendo</span>
      <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
        <form method="GET" class="inline">
          @if(request('applied_filters'))
            <input type="hidden" name="applied_filters" value="{{ request('applied_filters') }}">
          @endif
          @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
          @endif
          @if(request('page'))
            <input type="hidden" name="page" value="{{ request('page') }}">
          @endif

          <select 
            name="showing"
            onchange="this.form.submit()"
            class="select-entries dark:bg-dark-900 h-9 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none py-2 pl-3 pr-8 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 text-gray-500 dark:text-gray-400 inline"
          > 
            @if (isset($long) && $long)
            <option value="100" selected class="text-gray-500 dark:bg-gray-900 dark:text-gray-400">100</option>
            @else
            <option value="10" @if ($showing == 10) selected @endif>10</option>
            <option value="8" @if ($showing == 8) selected @endif>8</option>
            <option value="5" @if ($showing == 5) selected @endif>5</option>
            @endif
          </select>
        </form>
        <span class="absolute right-2 top-1/2 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
          <svg class="stroke-current" width="16" height="16" viewBox="0 0 16 16" fill="none">
            <path d="M3.8335 5.9165L8.00016 10.0832L12.1668 5.9165" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </span>
      </div>
      <span class="text-gray-500 dark:text-gray-300">entradas</span>
    </div>

    <div class="relative">
      <span class="pointer-events-none absolute top-1/2 left-4 -translate-y-1/2">
        <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20">
          <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"/>
        </svg>
      </span>
      <form method="GET" class="inline">
        @if(request('applied_filters'))
          <input type="hidden" name="applied_filters" value="{{ request('applied_filters') }}">
        @endif
        @if(request('showing'))
          <input type="hidden" name="showing" value="{{ request('showing') }}">
        @endif
        <input 
          type="text" 
          name="search" 
          value="{{ request('search') }}"
          placeholder="Buscar..." 
          class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-4 pl-[42px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden xl:w-[300px] dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
          onkeypress="if(event.key==='Enter') this.form.submit()"
        >
      </form>
    </div>
  </div>

 <!-- Tabla con scroll solo en horizontal -->
<div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 shadow-lg">
  <table class="min-w-full table-auto text-sm divide-y divide-gray-200 dark:divide-gray-700">
    <thead class="bg-gray-50 dark:bg-gray-800 text-left sticky top-0 z-10">
      <tr>
        @foreach ($columnas as $columna)
          <th class="px-5 py-3 font-semibold text-gray-800 dark:text-gray-100 whitespace-nowrap tracking-wide uppercase text-xs">
            {{ $columna }}
          </th>
        @endforeach
        @if (isset($actions))
          <th class="px-5 py-3 font-semibold text-gray-800 dark:text-gray-100 uppercase text-xs">
            Acción
          </th>
        @endif
      </tr>
    </thead>
    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
      @foreach($filas as $fila)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition duration-150 ease-in-out">
          @for($i = 0; $i < count($columnas); $i++)
            <td class="px-5 py-4 text-gray-700 dark:text-gray-300 max-w-[240px] truncate" title="{{ $fila[$i] }}">
              {{ $fila[$i] }}
            </td>
          @endfor
          <td class="px-5 py-4">
            <div class="flex flex-wrap gap-2">
              @foreach ($actions as $action)
                {!! $action->new($fila[0]) !!}
              @endforeach
            </div>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>


  <!-- Paginación -->
  @if (isset($paginaActual))
  <div class="flex w-full justify-center gap-8 p-4 items-center">
    @if ($paginaActual > 1)
      <button id="{{ $paginaActual - 1 }}" class="anterior-pag flex gap-2 text-gray-700 hover:bg-gray-100 bg-white dark:hover:text-gray-100 dark:text-gray-300 dark:bg-gray-700 border dark:border-gray-500 rounded-lg py-2 px-4">
        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none">
          <path fill-rule="evenodd" clip-rule="evenodd" d="M2.58301 9.99868C2.58272 10.1909 2.65588 10.3833 2.80249 10.53L7.79915 15.5301C8.09194 15.8231 8.56682 15.8233 8.85981 15.5305C9.15281 15.2377 9.15297 14.7629 8.86018 14.4699L5.14009 10.7472L16.6675 10.7472C17.0817 10.7472 17.4175 10.4114 17.4175 9.99715C17.4175 9.58294 17.0817 9.24715 16.6675 9.24715L5.14554 9.24715L8.86017 5.53016C9.15297 5.23717 9.15282 4.7623 8.85983 4.4695C8.56684 4.1767 8.09197 4.17685 7.79917 4.46984L2.84167 9.43049C2.68321 9.568 2.58301 9.77087 2.58301 9.99715Z"/>
        </svg>
        Anterior
      </button>
    @endif

    <p class="text-gray-700 dark:text-gray-300">Mostrando página {{ $paginaActual }} de {{ $totalPaginas }}</p>

    @if ($paginaActual < $totalPaginas)
      <button id="{{ $paginaActual + 1 }}" class="siguiente-pag flex gap-2 text-gray-700 hover:bg-gray-100 bg-white dark:hover:text-gray-100 dark:text-gray-300 dark:bg-gray-700 border dark:border-gray-500 rounded-lg py-2 px-4">
        Siguiente
        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none">
          <path fill-rule="evenodd" clip-rule="evenodd" d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4337 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715Z"/>
        </svg>
      </button>
    @endif
  </div>
  @endif
</div>

  
</div>

@push('custom-js')
  <script src="{{ asset('js/tables.js') }}"></script>
  <script src="{{ asset('js/delete-button-modal.js') }}"></script>
  <script src="{{ asset('js/download-button.js') }}"></script>
@endpush

