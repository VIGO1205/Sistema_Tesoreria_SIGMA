<table class="min-w-full">
    <thead class="">
        <tr class="border-gray-100 border-y dark:border-gray-800">
            @foreach($page->columns as $column)
                <th class="py-3">
                    <div class="flex items-center">
                        <p class="font-medium text-gray-900 text-theme-xs dark:text-gray-300">{{ $column }}</p>
                    </div>
                </th>
            @endforeach

            @if ($page->actions != null)
                <th class="py-3">
                    <div class="flex items-center">
                        <p class="font-medium text-gray-900 text-theme-xs dark:text-gray-300">
                        Acción
                        </p>
                    </div>
                </th>
            @endif
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
        @foreach($page->rows as $row)
            <tr>
                @for ($i = 0; $i < count($page->columns); $i++)
                    <td class="py-3">
                        <div class="flex items-center">
                            <p data-order="{{ $i }}" class="row{{ $row[0] }} text-gray-600 text-theme-sm dark:text-gray-400">
                                {{ $row[$i] }}
                            </p>
                        </div>
                    </td>
                @endfor

                @if (!empty($page->actions))
                    <td class="py-3">
                        <div class="flex gap-4 items-center">
                            @foreach ($page->actions as $action)
                                {{$action->new(...$row)}}
                            @endforeach
                        </div>
                    </td>
                @endif
            </tr>
        @endforeach

        <td class="py-3">
            <div class="flex gap-4 items-center">
                @if (isset($actions))
                    @foreach ($actions as $action)
                        {{$action->new($fila[0])}}
                    @endforeach
                @endif
            </div>
        </td>
    </tbody>
</table>

@if($page->paginator != null)
    {{ $page->paginator->render() }}
@endif