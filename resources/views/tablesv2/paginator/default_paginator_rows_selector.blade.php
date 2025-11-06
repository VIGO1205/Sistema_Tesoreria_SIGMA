<div class="flex items-center gap-3">
    <span class="text-gray-500 dark:text-gray-300">Viendo</span>
    <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
        <form method="GET" class="inline">
            {{-- Persist other query params except showing and page --}}
            @foreach(request()->except(['showing', 'page']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <select name="showing" onchange="this.form.submit()"
                class="select-entries dark:bg-dark-900 h-9 w-full appearance-none rounded-lg border border-gray-300 bg-transparent py-2 pl-3 pr-8 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 text-gray-500 dark:text-gray-400 inline">
                @foreach($page->options as $option)
                    <option value="{{$option}}" @if($page->valueSelected != null && $option == $page->valueSelected) selected @endif>{{$option}}</option>
                @endforeach
            </select>
        </form>
        <span class="absolute right-2 top-1/2 z-30 -translate-y-1/2 text-gray-500 dark:text-gray-400">
            @include('tablesv2.icons.down-arrow')
        </span>
    </div>
    <span class="text-gray-500 dark:text-gray-300">entradas</span>
</div>