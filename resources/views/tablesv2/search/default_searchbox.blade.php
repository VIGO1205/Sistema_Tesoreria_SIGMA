<div class="relative">
    <form method="GET" class="inline">
        <input type="text" name="search" @if($page->placeholder != null) placeholder="{{ $page->placeholder }}" @endif @if($page->value != null) value="{{ $page->value }}" @endif
            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-4 pl-[42px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden xl:w-[300px] dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
        {{-- Persist other query params except search and page --}}
        @foreach(request()->except(['search', 'page']) as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
    </form>
    <span class="pointer-events-none absolute top-1/2 left-4 -translate-y-1/2">
        @include('tablesv2.icons.glass')
    </span>
</div>