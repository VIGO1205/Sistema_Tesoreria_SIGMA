<?php

namespace App\Http\Controllers;

use App\Helpers\CRUDTablePage;
use App\Helpers\FilteredSearchQuery;
use App\Helpers\RequestHelper;
use App\Helpers\TableAction;
use App\Helpers\Tables\AdministrativoHeaderComponent;
use App\Helpers\Tables\AdministrativoSidebarComponent;
use App\Helpers\Tables\CautionModalComponent;
use App\Helpers\Tables\CRUDTableComponent;
use App\Helpers\Tables\FilterConfig;
use App\Helpers\Tables\PaginatorRowsSelectorComponent;
use App\Helpers\Tables\SearchBoxComponent;
use App\Helpers\Tables\TableButtonComponent;
use App\Helpers\Tables\TableComponent;
use App\Helpers\Tables\TablePaginator;
use App\Interfaces\ICronogramaAcademicoService;
use App\Interfaces\IExporterService;
use App\Interfaces\IExportRequestFactory;
use App\Models\Configuracion;
use App\Models\EstadoPeriodoAcademico;
use App\Models\PeriodoAcademico;
use Illuminate\Http\Request;

class PeriodoAcademicoController extends Controller
{

    protected ICronogramaAcademicoService $cronogramaService;

    public function __construct(ICronogramaAcademicoService $cronogramaService)
    {
        $this->cronogramaService = $cronogramaService;
    }

    private static function doSearch($sqlColumns, $search, $maxEntriesShow, $appliedFilters = [])
    {
        $columnMap = [
            'ID' => 'id_periodo_academico',
            'Nombre' => 'nombre',
        ];

        $query = PeriodoAcademico::query()->whereNot('id_estado_periodo_academico', '=', EstadoPeriodoAcademico::ANULADO)->orderByDesc('nombre');

        FilteredSearchQuery::fromQuery($query, $sqlColumns, $search, $appliedFilters, $columnMap);

        if ($maxEntriesShow === null) {
            return $query->get();
        }

        return $query->paginate($maxEntriesShow);
    }

    public function index(Request $request, $long = false)
    {
        $sqlColumns = ['id_periodo_academico', 'nombre'];
        $resource = 'administrativa';

        $params = RequestHelper::extractSearchParams($request);

        $page = CRUDTablePage::new()
            ->title("Períodos Académicos")
            ->sidebar(new AdministrativoSidebarComponent())
            ->header(new AdministrativoHeaderComponent());

        $content = CRUDTableComponent::new()
            ->title("Períodos Académicos");

        $filterButton = new TableButtonComponent("tablesv2.buttons.filtros");
        $content->addButton($filterButton);

        $descargaButton = new TableButtonComponent("tablesv2.buttons.download");
        $createNewEntryButton = new TableButtonComponent("tablesv2.buttons.createNewEntry", ["redirect" => "periodo_academico_create"]);

        if (!$long) {
            $vermasButton = new TableButtonComponent("tablesv2.buttons.vermas", ["redirect" => "periodo_academico_viewAll"]);
        } else {
            $vermasButton = new TableButtonComponent("tablesv2.buttons.vermenos", ["redirect" => "periodo_academico_view"]);
            $params->showing = 100;
        }

        $content->addButton($vermasButton);
        $content->addButton($descargaButton);
        $content->addButton($createNewEntryButton);

        $paginatorRowsSelector = new PaginatorRowsSelectorComponent();
        if ($long)
            $paginatorRowsSelector = new PaginatorRowsSelectorComponent([100]);
        $paginatorRowsSelector->valueSelected = $params->showing;
        $content->paginatorRowsSelector($paginatorRowsSelector);

        $searchBox = new SearchBoxComponent();
        $searchBox->placeholder = "Buscar período...";
        $searchBox->value = $params->search;
        $content->searchBox($searchBox);

        $cautionModal = CautionModalComponent::new()
            ->cautionMessage('¿Estás seguro?')
            ->action('Estás eliminando el Período Académico')
            ->columns(['Nombre', 'Estado'])
            ->rows(['', ''])
            ->lastWarningMessage('Borrar esto afectará a todas las matrículas vinculadas a este período.')
            ->confirmButton('Sí, bórralo')
            ->cancelButton('Cancelar')
            ->isForm(true)
            ->dataInputName('id')
            ->build();

        $page->modals([$cautionModal]);

        $query = static::doSearch($sqlColumns, $params->search, $params->showing, $params->applied_filters);

        if ($params->page > $query->lastPage()) {
            $params->page = 1;
            $query = static::doSearch($sqlColumns, $params->search, $params->showing, $params->applied_filters);
        }

        $filterConfig = new FilterConfig();
        $filterConfig->filters = ["ID", "Nombre"];
        $content->filterConfig = $filterConfig;

        $table = new TableComponent();
        $table->columns = ["ID", "Nombre", "Estado"];
        $table->rows = [];

        foreach ($query as $periodo) {
            $esActual = $periodo->id_periodo_academico == $this->cronogramaService->periodoActual()->getKey();

            array_push($table->rows, [
                $periodo->id_periodo_academico,
                $periodo->nombre,
                $esActual ? 'ACTUAL' : $periodo->estado->nombre,
            ]);
        }

        $table->actions = [
            new TableAction('edit', 'periodo_academico_edit', $resource),
            new TableAction('delete', '', $resource),
        ];

        $paginator = new TablePaginator($params->page, $query->lastPage(), [
            'search' => $params->search,
            'showing' => $params->showing,
            'applied_filters' => $params->applied_filters
        ]);
        $table->paginator = $paginator;

        $content->tableComponent($table);
        $page->content($content->build());

        return $page->render();
    }

    public function viewAll(Request $request)
    {
        return static::index($request, true);
    }

    public function create(Request $request)
    {
        $data = [
            'return' => route('periodo_academico_view', ['abort' => true]),
        ];

        return view('gestiones.periodo_academico.create', compact('data'));
    }

    public function createNewEntry(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:periodos_academicos,nombre',
        ], [
            'nombre.required' => 'El nombre del período es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 50 caracteres.',
            'nombre.unique' => 'Ya existe un período con este nombre.',
        ]);

        $periodo = PeriodoAcademico::create([
            'nombre' => $request->input('nombre'),
            'id_estado_periodo_academico' => $request->input('estado_del_período')
        ]);

        if (PeriodoAcademico::count() == 1) {
            $this->cronogramaService->establecerPeriodoActual($periodo);
        }

        return redirect()->route('periodo_academico_view', ['created' => true]);
    }

    public function edit(Request $request, int $id)
    {
        return view('gestiones.periodo_academico.edit', ['id' => $id]);
    }

    public function editEntry(Request $request, $id)
    {
        if (!isset($id)) {
            return redirect(route('periodo_academico_view'));
        }

        $request->validate([
            'nombre' => 'required|string|max:50|unique:periodos_academicos,nombre,' . $id . ',id_periodo_academico',
        ], [
            'nombre.required' => 'El nombre del período es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 50 caracteres.',
            'nombre.unique' => 'Ya existe un período con este nombre.',
        ]);

        $periodo = PeriodoAcademico::findOrFail($id);

        $periodo->update([
            'nombre' => $request->input('nombre'),
        ]);

        // Si se marcó como actual
        if ($request->input('establecer_actual') == '1') {
            $periodo->establecerComoActual();
        }

        return redirect()->route('periodo_academico_view', ['edited' => true]);
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');

        $periodo = PeriodoAcademico::findOrFail($id);

        if ($this->cronogramaService->esActual($periodo)) {
            return redirect()->route('periodo_academico_view')
                ->withErrors('No se puede eliminar el período académico actual.');
        }

        $periodo->anular();

        return redirect()->route('periodo_academico_view', ['deleted' => true]);
    }

    public function establecerActual(Request $request, $id)
    {
        $periodo = PeriodoAcademico::findOrFail($id);
        $this->cronogramaService->establecerPeriodoActual($periodo);

        return redirect()->route('periodo_academico_view');
    }

    public function export(Request $request, IExportRequestFactory $requestFactory, IExporterService $exporterService)
    {
        $sqlColumns = ['id_periodo_academico', 'nombre'];

        $params = RequestHelper::extractSearchParams($request);
        $query = static::doSearch($sqlColumns, $params->search, null, $params->applied_filters);

        $periodoActualId = Configuracion::obtener('ID_PERIODO_ACADEMICO_ACTUAL');

        $data = $query->map(function ($periodo) use ($periodoActualId) {
            return [
                $periodo->id_periodo_academico,
                $periodo->nombre,
                $periodo->id_periodo_academico == $periodoActualId ? 'Actual' : '—',
            ];
        });

        $title = 'Listado de Períodos Académicos';
        $headers = ['ID', 'Nombre', 'Estado'];

        $exportRequest = $requestFactory->create(
            $title,
            $headers,
            $data->toArray(),
            ['filename' => 'periodos_academicos_' . date('d_m_Y')]
        );

        return $exporterService->exportAsResponse($request, $exportRequest);
    }
}