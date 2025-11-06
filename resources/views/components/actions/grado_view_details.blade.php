@if (is_null($resource) || Gate::allows('manage-resource', [$resource, 'view']))
    <a href="{{ route($routeName, [$params[0]]) }}" class="text-gray-600 text-theme-sm dark:text-gray-400">
        Ver detalles
    </a>
@endif
