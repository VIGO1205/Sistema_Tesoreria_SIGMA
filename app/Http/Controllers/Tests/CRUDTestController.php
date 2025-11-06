<?php

namespace App\Http\Controllers\Tests;

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
use App\Http\Controllers\Controller;
use App\Models\NivelEducativo;
use \Illuminate\Http\Request;

class CRUDTestController extends Controller{
    private static function doSearch($sqlColumns, $search, $maxEntriesShow, $appliedFilters = []){
        $columnMap = [
            'ID' => 'id_nivel',
            'Nivel' => 'nombre_nivel',
            'Descripción' => 'descripcion'
        ];

        $query = NivelEducativo::where('estado', '=', true);

        FilteredSearchQuery::fromQuery($query, $sqlColumns, $search, $appliedFilters, $columnMap);

        if ($maxEntriesShow == null) return $query->get();

        return $query->paginate($maxEntriesShow);
    }

    public function index(Request $request){
        $sqlColumns = ['id_nivel', 'nombre_nivel', 'descripcion'];
        $resource = 'academica';

        $params = RequestHelper::extractSearchParams($request);
        
        $page = CRUDTablePage::new();
        $page->title("Niveles Educativos");
        $page->sidebar(new AdministrativoSidebarComponent());
        $page->header(new AdministrativoHeaderComponent());
        
        $content = CRUDTableComponent::new();
        $content->title("Niveles Educativos");
        $filterButton = new TableButtonComponent("tablesv2.buttons.filtros");
        $content->addButton($filterButton);

        if ($request->viendotodos != null && !$request->viendotodos){
            $vermasButton = new TableButtonComponent("tablesv2.buttons.vermas", ["redirect" => "nivel_educativo_viewAll"]);
        } else if ($request->viendotodos != null && $request->viendotodos){
            $vermasButton = new TableButtonComponent("tablesv2.buttons.vermenos", ["redirect" => "nivel_educativo_view"]);
        }

        $content->addButton($vermasButton);

        $descargaButton = new TableButtonComponent("tablesv2.buttons.download");
        $content->addButton($descargaButton);
        
        $createNewEntryButton = new TableButtonComponent("tablesv2.buttons.createNewEntry", ["redirect" => "nivel_educativo_create"]);
        $content->addButton($createNewEntryButton);

        $paginatorRowsSelector = new PaginatorRowsSelectorComponent();
        $paginatorRowsSelector->valueSelected = $params->showing;
        $content->paginatorRowsSelector($paginatorRowsSelector);

        $searchBox = new SearchBoxComponent();
        $searchBox->placeholder = "Buscar...";
        $searchBox->value = $params->search;
        $content->searchBox($searchBox);

        $cautionModal = CautionModalComponent::new()
            ->cautionMessage('¿Estás seguro?')
            ->action('Estás eliminando el Nivel Educativo')
            ->columns(['Nivel', 'Descripción'])
            ->rows(['nombre', 'descripcion'])
            ->lastWarningMessage('Borrar esto afectará a todo lo que esté vinculado a este Nivel Educativo')
            ->confirmButton('Sí, bórralo')
            ->cancelButton('Cancelar')
            ->isForm(true)
            ->dataInputName('id')
            ->build();

        $page->modals([$cautionModal]);
        
        $query = static::doSearch($sqlColumns, $params->search, $params->showing, $params->applied_filters);

        if ($params->page > $query->lastPage()){
            $params->page = 1;
            $query = static::doSearch($sqlColumns, $params->search, $params->showing, $params->applied_filters);
        }

        $nivelesExistentes = NivelEducativo::select("nombre_nivel")
            ->distinct()
            ->where("estado", "=", 1)
            ->pluck("nombre_nivel");

        $filterConfig = new FilterConfig();
        $filterConfig->filters = [
            "ID", "Nivel", "Descripción"
        ];
        $filterConfig->filterOptions = [
            "Nivel" => $nivelesExistentes
        ];
        $content->filterConfig = $filterConfig;
        
        $table = new TableComponent();
        $table->columns = ["ID", "Nivel", "Descripción"];
        $table->rows = [];

        foreach ($query as $nivel){
            array_push($table->rows,
            [
                $nivel->id_nivel,
                $nivel->nombre_nivel,
                $nivel->descripcion
            ]); 
        }
        $table->actions = [
            new TableAction('edit', 'nivel_educativo_edit', $resource),
            new TableAction('delete', '', $resource),
        ];

        $paginator = new TablePaginator($params->page, $query->lastPage(), []);
        $table->paginator = $paginator;

        $content->tableComponent($table);

        $page->content($content->build());

        return $page->render();
    }
}