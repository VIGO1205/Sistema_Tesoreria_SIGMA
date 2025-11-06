<div x-data="{ 
  showFilters: @js(request('applied_filters') && json_decode(request('applied_filters', '[]'), true) !== [])
}">
  <!-- Componente de filtros que se muestra/oculta -->
  <div x-show="showFilters" x-transition>
    @include('tablesv2.filters.filters_menu', ["options" => $page->filterConfig])
    <br>
  </div>

  <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
    <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
          {{$page->title}}
        </h3>
      </div>

      <div class="flex items-center gap-3">
        <!-- Botones -->
        @foreach ($page->buttons as $button)
          {{ $button->render() }}
        @endforeach
      </div>
    </div>

    <div class="w-full overflow-x-auto text-[0.9rem]">
      <div class="flex items-center justify-between pb-4">
        @if($page->paginatorRowsSelector != null)
          {{ $page->paginatorRowsSelector->render() }}
        @endif

        @if($page->searchBox != null)
          {{ $page->searchBox->render() }}
        @endif
      </div>

        @if($page->tableComponent != null)
          {{ $page->tableComponent->render() }}
        @endif
    </div>
</div>

<script src="{{ asset('js/tables.js')}}"></script>
<script src="{{ asset('js/delete-button-modal.js') }}"></script>
<script src="{{ asset('js/download-button.js') }}"></script>
