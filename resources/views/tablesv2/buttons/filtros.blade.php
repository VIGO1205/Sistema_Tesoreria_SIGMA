<button @click="showFilters = !showFilters"
    :class="showFilters ? 'bg-blue-50 border-blue-300 text-blue-700 dark:bg-blue-900/20 dark:border-blue-700 dark:text-blue-300' : 'bg-white border-gray-300 text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400'"
    class="inline-flex items-center gap-2 rounded-lg border px-4 py-2.5 text-theme-sm font-medium shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 transition-colors duration-200">
    
    @include('tablesv2.icons.filters')

    <span x-text="showFilters ? 'Ocultar filtros' : 'Filtros'"></span>
</button>