<!-- Botón Crear nuevo registro -->
<a @if ($page->action != null && $page->action["redirect"] != null) href="{{ route($page->action["redirect"]) }}" @endif
    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-gray-200 px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-300 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
    Crear un nuevo registro
</a>