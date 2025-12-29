<a @if ($page->action != null && $page->action["redirect"] != null) href="{{ route($page->action["redirect"]) }}" @endif
    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-gray-500 px-4 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-gray-600 dark:border-gray-700 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
    Volver
</a>