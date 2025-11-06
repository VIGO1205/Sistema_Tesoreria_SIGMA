<div x-data="{ showDownloadMenu: false }" class="relative">
    <button @click="showDownloadMenu = !showDownloadMenu"
        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
        <svg class="h-5 w-5 fill-current">
            @include('tablesv2.icons.download')
        </svg>
        Descargar
        <svg class="h-4 w-4 fill-current transition-transform duration-200" :class="{ 'rotate-180': showDownloadMenu }">
            @include('tablesv2.icons.down-arrow')
        </svg>
    </button>

    <div x-show="showDownloadMenu" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95" @click.away="showDownloadMenu = false"
        class="absolute right-0 z-50 mt-2 w-48 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 dark:bg-gray-800 dark:ring-gray-700">
        <div class="py-1">
            <button type="button" onclick="downloadExport('excel')"
                class="group flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                @include('tablesv2.icons.excel')
                Descargar Excel
            </button>
            <button type="button" onclick="downloadExport('pdf')"
                class="group flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                @include('tablesv2.icons.pdf')
                Descargar PDF
            </button>
        </div>
    </div>
</div>