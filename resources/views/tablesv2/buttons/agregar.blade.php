<a @if ($page->action != null && $page->action["redirect"] != null) 
    href="{{ route($page->action["redirect"], $page->action["params"] ?? []) }}" 
@endif
    class="inline-flex items-center gap-2 rounded-lg border border-green-300 bg-green-500 px-4 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-green-600 dark:border-green-700 dark:bg-green-600 dark:text-gray-200 dark:hover:bg-green-700">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
    </svg>
    Agregar Docente
</a>