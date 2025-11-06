<div>
    <label for="" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
        {{ $label }}
    </label>
    <input 
        @if(isset($placeholder)) placeholder="{{ $placeholder }}" @endif 
        @if(isset($value)) value="{{ old($name, $value ?? '') }}" @endif
        type="text" 
        name="{{ $name ?? Str::snake($label) }}"
        @if(!empty($readonly) && $readonly) readonly @endif

        class="text-{{$label}} h-11 w-full rounded-lg px-4 py-2.5 text-sm shadow-theme-xs focus:outline-none
            @if(!empty($readonly) && $readonly)
                bg-gray-100 text-gray-500 border border-gray-300 cursor-not-allowed dark:bg-gray-800 dark:text-white/40 dark:border-gray-700
            @else
                bg-white text-gray-800 border border-gray-300 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10
                dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800
            @endif"
    />
</div>