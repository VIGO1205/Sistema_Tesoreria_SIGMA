@if (is_null($resource) || Gate::allows('manage-resource', [$resource, 'download']))
    <a href="{{ route($routeName, [$params[0]]) }}" class="text-gray-600 text-theme-sm dark:text-gray-400">
        Generar constancia
    </a>
@endif