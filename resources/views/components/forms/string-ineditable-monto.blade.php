<div>
    @if ($error)
      <p class="py-2 text-red-700 dark:text-red-200">{{ $error }}</p>
    @endif

    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
        {{ $label }}
    </label>
    <input
        type="text"
        name="{{ $name ?? Str::snake($label) }}"
        id="{{ $attributes['id'] ?? '' }}"
        value="{{ old($name, $attributes['value'] ?? $value ?? '') }}"
        @if(isset($attributes['readonly']) && $attributes['readonly']) readonly @endif
        class="w-full bg-gray-100 dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 px-4 py-2.5 text-sm text-gray-800 dark:text-white/90"
    />
</div>