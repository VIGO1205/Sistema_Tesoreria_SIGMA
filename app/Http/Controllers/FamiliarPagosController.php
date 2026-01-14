<?php

namespace App\Http\Controllers;

use App\Models\ConceptoPago;
use App\Models\Deuda;
use App\Models\Pago;
use Illuminate\Http\Request;

use App\Helpers\Home\Familiar\FamiliarHeaderComponent;
use App\Helpers\Home\Familiar\FamiliarSidebarComponent;
use App\Http\Controllers\Home\Utils;
use App\Models\Grado;
use App\Models\Matricula;
use App\Models\Seccion;
use App\Helpers\CRUDTablePage;
use App\Helpers\ExcelExportHelper;
use App\Helpers\FilteredSearchQuery;
use App\Helpers\PDFExportHelper;
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
use App\Http\Controllers\Controller;
use App\Models\NivelEducativo;

class FamiliarPagosController extends Controller
{
    private static function doSearchDeudas($sqlColumns, $search, $maxEntriesShow, $appliedFilters = [], Request $request){
        $columnMap = [
            "ID" => "id_deuda",
            "Concepto" => "ConceptoPago.descripcion",
            "Periodo" => "periodo",
            "Monto" => "monto_total",
        ];

        $query = Deuda::where('estado', '=', true);

        $requested = $request->session()->get('alumno');
        $query->where('id_alumno', '=', $requested->getKey());


        FilteredSearchQuery::fromQuery($query, $sqlColumns, $search, $appliedFilters, $columnMap);

        if ($maxEntriesShow == null) return $query->get();

        return $query->paginate($maxEntriesShow);
    }

    public function indexDeudas(Request $request, $long = false){
        $requested = $request->session()->get('alumno');

        if ($requested == null){
            return redirect(route('principal'));
        }

        $sqlColumns = ['id_deuda', 'ConceptoPago.descripcion', 'periodo', 'fecha_limite', 'monto_total'];
        $resource = 'pagos';

        $params = RequestHelper::extractSearchParams($request);

        $header = Utils::crearHeaderConAlumnos($request);

        $page = CRUDTablePage::new()
            ->title("Deudas")
            ->header($header)
            ->sidebar(new FamiliarSidebarComponent());

        $content = CRUDTableComponent::new()
            ->title("Deudas de tu alumno");

        $filterButton = new TableButtonComponent("tablesv2.buttons.filtros");
        $content->addButton($filterButton);

        $pagoButton = new TableButtonComponent("tablesv2.buttons.realizar-pago");
        $content->addButton($pagoButton);

        /* Paginador */
        $paginatorRowsSelector = new PaginatorRowsSelectorComponent();
        if ($long) $paginatorRowsSelector = new PaginatorRowsSelectorComponent([100]);
        $paginatorRowsSelector->valueSelected = $params->showing;
        $content->paginatorRowsSelector($paginatorRowsSelector);

        /* Searchbox */
        $searchBox = new SearchBoxComponent();
        $searchBox->placeholder = "Buscar...";
        $searchBox->value = $params->search;
        $content->searchBox($searchBox);

        /* Lógica del controller */

        $query = static::doSearchDeudas($sqlColumns, $params->search, $params->showing, $params->applied_filters, $request);

        if ($params->page > $query->lastPage()){
            $params->page = 1;
            $query = static::doSearchDeudas($sqlColumns, $params->search, $params->showing, $params->applied_filters);
        }

        $periodosExistentes = Deuda::select("periodo")
            ->distinct()
            ->where("estado", "=", 1)
            ->pluck("periodo");


        $filterConfig = new FilterConfig();
        $filterConfig->filters = [
            "ID", "Concepto", "Periodo", "Monto"
        ];
        $filterConfig->filterOptions = [
            "Periodo" => $periodosExistentes,
        ];
        $content->filterConfig = $filterConfig;

        $table = new TableComponent();
        $table->columns = ["ID", "Concepto", "Periodo", "Fecha Límite", "Monto"];
        $table->rows = [];

        foreach ($query as $deuda){
            $totalpagado = Pago::where('id_deuda', $deuda->id_deuda)
                ->where('estado', true)
                ->sum('monto');
            if ($totalpagado >= $deuda->monto_total) continue;
            array_push($table->rows,
            [
                $deuda->id_deuda,
                $deuda->concepto->descripcion,
                $deuda->periodo,
                \Carbon\Carbon::parse($deuda->fecha_limite)->format('d/m/Y'),
                $deuda->monto_total,
            ]);
        }

        $paginator = new TablePaginator($params->page, $query->lastPage(), []);
        $table->paginator = $paginator;

        $content->tableComponent($table);

        $page->content($content->build());

        return $page->render();
    }

    private static function doSearchPagos($sqlColumns, $search, $maxEntriesShow, $appliedFilters = [], Request $request){
        $columnMap = [
            'ID' => 'id_pago',
            'Por el periodo' => 'Deuda.periodo',
            'Monto' => 'monto',
        ];

        $query = Pago::where('estado', '=', true);

        $requested = $request->session()->get('alumno');
        $query->whereHas('deuda', function($q) use ($requested) {
            $q->where('id_alumno', '=', $requested->getKey());
        });

        FilteredSearchQuery::fromQuery($query, $sqlColumns, $search, $appliedFilters, $columnMap);

        if ($maxEntriesShow == null) return $query->get();

        return $query->paginate($maxEntriesShow);
    }

    public function indexPagos(Request $request, $long = false){
        $requested = $request->session()->get('alumno');

        if ($requested == null){
            return redirect(route('principal'));
        }

        $sqlColumns = ['id_pago', 'Deuda.periodo', 'fecha_pago', 'monto', 'observaciones'];
        $resource = 'pagos';

        $params = RequestHelper::extractSearchParams($request);

        $header = Utils::crearHeaderConAlumnos($request);

        $page = CRUDTablePage::new()
            ->title("Pagos")
            ->header($header)
            ->sidebar(new FamiliarSidebarComponent());

        $content = CRUDTableComponent::new()
            ->title("Pagos de tu alumno");

        $filterButton = new TableButtonComponent("tablesv2.buttons.filtros");
        $content->addButton($filterButton);

        /* Paginador */
        $paginatorRowsSelector = new PaginatorRowsSelectorComponent();
        if ($long) $paginatorRowsSelector = new PaginatorRowsSelectorComponent([100]);
        $paginatorRowsSelector->valueSelected = $params->showing;
        $content->paginatorRowsSelector($paginatorRowsSelector);

        /* Searchbox */
        $searchBox = new SearchBoxComponent();
        $searchBox->placeholder = "Buscar...";
        $searchBox->value = $params->search;
        $content->searchBox($searchBox);

        /* Lógica del controller */

        $query = static::doSearchPagos($sqlColumns, $params->search, $params->showing, $params->applied_filters, $request);

        if ($params->page > $query->lastPage()){
            $params->page = 1;
            $query = static::doSearchPagos($sqlColumns, $params->search, $params->showing, $params->applied_filters);
        }

        $periodosExistentes = Deuda::select("periodo")
            ->distinct()
            ->where("estado", "=", 1)
            ->pluck("periodo");

        $filterConfig = new FilterConfig();
        $filterConfig->filters = [
            "ID", "Por el periodo", "Monto"
        ];
        $filterConfig->filterOptions = [
            "Por el periodo" => $periodosExistentes,
        ];
        $content->filterConfig = $filterConfig;

        $table = new TableComponent();
        $table->columns = ["ID", "Período", "Fecha de Pago", "Monto", "Observaciones"];
        $table->rows = [];

        foreach ($query as $pago){
            array_push($table->rows,
            [
                $pago->id_pago,
                $pago->deuda->periodo,
                \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y'),
                $pago->monto,
                $pago->observaciones ?? 'No registra.'
            ]);
        }

        $paginator = new TablePaginator($params->page, $query->lastPage(), []);
        $table->paginator = $paginator;

        $content->tableComponent($table);

        $page->content($content->build());

        return $page->render();
    }
}
