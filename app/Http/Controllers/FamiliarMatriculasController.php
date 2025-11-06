<?php

namespace App\Http\Controllers;

use App\Helpers\Home\Familiar\FamiliarHeaderComponent;
use App\Helpers\Home\Familiar\FamiliarSidebarComponent;
use App\Http\Controllers\Home\Utils;
use App\Models\Grado;
use App\Models\Matricula;
use App\Models\Seccion;
use Illuminate\Http\Request;
use App\Helpers\ArrayableTableAction;
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

class FamiliarMatriculasController extends Controller
{
    private static function doSearch($sqlColumns, $search, $maxEntriesShow, $appliedFilters = [], Request $request){
        $columnMap = [
            'ID' => 'id_matricula',
            'Año Escolar' => 'año_escolar',
            'Escala' => 'escala',
            'Grado' => 'Grado.nombre_grado',
            'Sección' => 'seccion.nombreSeccion',
        ];

        $query = Matricula::where('estado', '=', true);

        $requested = $request->session()->get('alumno');
        $query->where('id_alumno', '=', $requested->getKey());

        FilteredSearchQuery::fromQuery($query, $sqlColumns, $search, $appliedFilters, $columnMap);

        if ($maxEntriesShow == null) return $query->get();

        return $query->paginate($maxEntriesShow);
    }
    
    public function index(Request $request, $long = false){
        $requested = $request->session()->get('alumno');

        if ($requested == null){
            return redirect(route('principal'));
        }

        $sqlColumns = ['id_matricula', 'año_escolar', 'fecha_matricula', 'escala', 'Grado.nombre_grado', 'seccion.nombreSeccion'];
        $resource = 'pagos';

        $params = RequestHelper::extractSearchParams($request);
        
        $header = Utils::crearHeaderConAlumnos($request);

        $page = CRUDTablePage::new()
            ->title("Matrículas")
            ->header($header)
            ->sidebar(new FamiliarSidebarComponent());
        
        $content = CRUDTableComponent::new()
            ->title("Matrículas de tu alumno");

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
        
        $query = static::doSearch($sqlColumns, $params->search, $params->showing, $params->applied_filters, $request);

        if ($params->page > $query->lastPage()){
            $params->page = 1;
            $query = static::doSearch($sqlColumns, $params->search, $params->showing, $params->applied_filters);
        }

        $gradosExistentes = Grado::select("nombre_grado")
            ->distinct()
            ->where("estado", "=", 1)
            ->pluck("nombre_grado");

        $seccionesExistentes = Seccion::select("nombreSeccion")
            ->distinct()
            ->where("estado", "=", 1)
            ->pluck("nombreSeccion");


        $filterConfig = new FilterConfig();
        $filterConfig->filters = [
            "ID", "Año Escolar", "Escala", "Grado", "Sección"
        ];
        $filterConfig->filterOptions = [
            "Escala" => ["A", "B", "C", "D", "E"],
            "Grado" => $gradosExistentes,
            "Sección" => $seccionesExistentes,
        ];
        $content->filterConfig = $filterConfig;
        
        $table = new TableComponent();
        $table->columns = ["ID", "Año Escolar", "Fecha de Matrícula", "Escala", "Grado", "Sección", "Observaciones"];
        $table->rows = [];

        foreach ($query as $matricula){
            array_push($table->rows,
            [
                $matricula->id_matricula,
                $matricula->año_escolar,
                \Carbon\Carbon::parse($matricula->fecha_matricula)->format('d/m/Y'),
                $matricula->escala,
                $matricula->grado->nombre_grado,
                $matricula->seccion->nombreSeccion,
                $matricula->observaciones ?? 'No registra.'
            ]); 
        }

        $paginator = new TablePaginator($params->page, $query->lastPage(), []);
        $table->paginator = $paginator;

        $content->tableComponent($table);

        $page->content($content->build());

        return $page->render();
    }
}
