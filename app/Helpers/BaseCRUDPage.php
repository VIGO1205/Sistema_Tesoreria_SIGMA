<?php

namespace App\Helpers;

use App\Helpers\CRUDTablePage;
use App\Helpers\Tables\AdministrativoHeaderComponent;
use App\Helpers\Tables\AdministrativoSidebarComponent;
use App\Helpers\Tables\CautionModalComponent;
use App\Helpers\Tables\CRUDTableComponent;
use App\Helpers\Tables\FilterConfig;
use App\Helpers\Tables\PaginatorRowsSelectorComponent;
use App\Helpers\Tables\SearchBoxComponent;
use App\Helpers\Tables\TableButtonComponent;
use App\Helpers\Tables\TableComponent;

class BaseCRUDPage{
    public static function create($params){
        $page = CRUDTablePage::new();
        $page->title("Título por defecto");
        $page->sidebar(new AdministrativoSidebarComponent());
        $page->header(new AdministrativoHeaderComponent());
        
        $content = CRUDTableComponent::new();
        $content->title("Nombre de tabla por defecto");
        $filterButton = new TableButtonComponent("tablesv2.buttons.filtros");
        $content->addButton($filterButton);

        $vermasButton = new TableButtonComponent("tablesv2.buttons.vermas", ["redirect" => "nivel_educativo_viewAll"]);

        if ($params->viendotodos != null && $params->viendotodos){
            $vermasButton = new TableButtonComponent("tablesv2.buttons.vermenos", ["redirect" => "nivel_educativo_view"]);
        }

        $content->addButton($vermasButton);

        $descargaButton = new TableButtonComponent("tablesv2.buttons.download");
        $content->addButton($descargaButton);
        
        $createNewEntryButton = new TableButtonComponent("tablesv2.buttons.createNewEntry", ["redirect" => "nivel_educativo_create"]);
        $content->addButton($createNewEntryButton);

        $paginatorRowsSelector = new PaginatorRowsSelectorComponent();
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

        $filterConfig = new FilterConfig();
        $content->filterConfig = $filterConfig;
        
        $table = new TableComponent();
        $content->tableComponent($table);

        $page->content($content->build());

        return $page;
    }
}
