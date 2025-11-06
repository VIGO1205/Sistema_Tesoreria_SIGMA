<div
  class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6"
  x-data="{
    activeFilters: @js(json_decode(request('applied_filters', '[]'), true) ?? []),
    showNewFilter: false,
    selectedFilter: '',
    filterValue: '',
    availableFilters: @js($options->filters ?? []),
    filterOptions: @js($options->filterOptions ?? []),
    
    isComboboxFilter(filterName) {
      return this.filterOptions.hasOwnProperty(filterName);
    },
    
    getFilterOptions(filterName) {
      return this.filterOptions[filterName] || [];
    },
    
    submitFilters() {
      // Actualizar el valor del input hidden antes de enviar
      document.querySelector('input[name=applied_filters]').value = JSON.stringify(this.activeFilters);
      document.getElementById('filters-form').submit();
    },
    
    addFilter() {
      if (this.selectedFilter && this.filterValue.trim()) {
        // Verificar si el filtro ya existe
        const existingIndex = this.activeFilters.findIndex(f => f.key === this.selectedFilter);
        
        if (existingIndex >= 0) {
          // Actualizar filtro existente
          this.activeFilters[existingIndex].value = this.filterValue.trim();
        } else {
          // Agregar nuevo filtro
          this.activeFilters.push({
            key: this.selectedFilter,
            name: this.selectedFilter,
            value: this.filterValue.trim()
          });
        }
        
        this.selectedFilter = '';
        this.filterValue = '';
        this.showNewFilter = false;
        this.submitFilters();
      }
    },
    
    removeFilter(index) {
      this.activeFilters.splice(index, 1);
      this.submitFilters();
    },
    
    clearFilters() {
      this.activeFilters = [];
      this.submitFilters();
    }
  }"
>
  <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
        Filtros avanzados
      </h3>
    </div>
  </div>

  <!-- Formulario para envío automático de filtros -->
  <form id="filters-form" method="GET" style="display: none;">
    <input type="hidden" name="applied_filters" :value="JSON.stringify(activeFilters)">
    <!-- Mantener otros parámetros de la URL actual -->
    @foreach(request()->except(['applied_filters', 'page']) as $key => $value)
      <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach
  </form>



  <div class="w-full text-[0.9rem] space-y-4">
    <!-- Filtros activos -->
    <div x-show="activeFilters.length > 0" class="space-y-2">
      <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Filtros aplicados:</h4>
      <div class="flex flex-wrap gap-2">
        <template x-for="(filter, index) in activeFilters" :key="index">
          <div class="flex items-center gap-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg px-3 py-2">
            <span class="text-sm text-blue-800 dark:text-blue-200" x-text="filter.name + ': ' + filter.value"></span>
            <button 
              @click="removeFilter(index)" 
              class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
        </template>
      </div>
    </div>

    <!-- Botón para añadir nuevo filtro -->
    <div class="flex items-center gap-4">
      <button 
        @click="showNewFilter = !showNewFilter"
        class="bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600 text-white font-medium py-2.5 px-4 rounded-lg transition-colors duration-200 flex items-center gap-2"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Añadir un nuevo filtro
      </button>

      <!-- Botón para limpiar todos los filtros -->
      <button 
        x-show="activeFilters.length > 0"
        @click="clearFilters()"
        class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-500 text-white font-medium py-2.5 px-4 rounded-lg transition-colors duration-200"
      >
        Limpiar filtros
      </button>
    </div>

    <!-- Formulario para nuevo filtro -->
    <div x-show="showNewFilter" x-transition class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Selector de filtro -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Tipo de filtro
          </label>
          <select 
            x-model="selectedFilter"
            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm text-gray-800 dark:text-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none"
          >
            <option value="">Seleccionar filtro...</option>
            <template x-for="filter in availableFilters.filter(f => !activeFilters.some(af => af.key === f))" :key="filter">
              <option :value="filter" x-text="filter"></option>
            </template>
          </select>
        </div>

        <!-- Campo de valor -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Valor
          </label>
          
          <!-- Campo de texto (por defecto) -->
          <div x-show="!selectedFilter || !isComboboxFilter(selectedFilter)">
            <input 
              type="text"
              x-model="filterValue"
              placeholder="Ingrese el valor a filtrar..."
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm text-gray-800 dark:text-gray-200 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none"
            >
          </div>
          
          <!-- Combobox (para filtros con opciones predefinidas) -->
          <div x-show="selectedFilter && isComboboxFilter(selectedFilter)">
            <select 
              x-model="filterValue"
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2 text-sm text-gray-800 dark:text-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none"
            >
              <option value="">Seleccionar valor...</option>
              <template x-for="option in getFilterOptions(selectedFilter)" :key="option">
                <option :value="option" x-text="option"></option>
              </template>
            </select>
          </div>
        </div>

        <!-- Botones de acción -->
        <div class="flex items-end gap-2">
          <button 
            @click="addFilter()"
            :disabled="!selectedFilter || !filterValue.trim()"
            class="bg-green-600 hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200"
          >
            Agregar
          </button>
          <button 
            @click="showNewFilter = false; selectedFilter = ''; filterValue = ''"
            class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200"
          >
            Cancelar
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
