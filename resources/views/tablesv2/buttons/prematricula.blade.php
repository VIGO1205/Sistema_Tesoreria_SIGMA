<a @if ($page->action != null && $page->action["redirect"] != null) href="{{ route($page->action["redirect"]) }}" @endif
    class="inline-flex items-center gap-2 rounded-lg border border-green-500 bg-green-500 px-4 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-green-600 dark:border-green-600 dark:bg-green-600 dark:hover:bg-green-700">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
    </svg>
    Registrar Prematrícula
</a>