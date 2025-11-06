@if (is_null($resource) || Gate::allows('manage-resource', [$resource, 'view']))
    <a href="{{ route($routeName, ['id_grado' => $params[4], 'nombreSeccion' => $params[3]]) }}" class="text-gray-600 text-theme-sm dark:text-gray-400">
        Ver detalles
    </a>
@endif