@if (is_null($resource) || Gate::allows('manage-resource', [$resource, 'delete']))
    <button data-id="{{$params[0]}}" class="delete-button text-gray-600 text-theme-sm dark:text-gray-400">
        Eliminar
    </button>
@endcan 