@if (is_null($resource) || Gate::allows('manage-resource', [$resource, 'edit']))
    <a href="{{ route($routeName, [$params[0]]) }}" class="edit-button text-gray-600 text-theme-sm dark:text-gray-400">
        Editar
    </a>
@endif
