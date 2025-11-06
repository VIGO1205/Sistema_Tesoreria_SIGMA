<?php

namespace App\Helpers\Tables;

use App\Helpers\Tables\Component;

class CRUDTableComponent implements Component
{
    public string $title;
    public array $buttons = [];
    public ?Component $paginatorRowsSelector = null;
    public ?Component $searchBox = null;
    public ?TableComponent $tableComponent = null;
    public ?FilterConfig $filterConfig = null;

    public function __construct(
        string $title = '',
        array $buttons = [],
        ?Component $paginatorRowsSelector = null,
        ?Component $searchBox = null,
        ?TableComponent $tableComponent = null,
        ?FilterConfig $filterConfig = null,
    ) {
        $this->title = $title;
        $this->buttons = $buttons;
        $this->paginatorRowsSelector = $paginatorRowsSelector;
        $this->searchBox = $searchBox;
        $this->tableComponent = $tableComponent;
        $this->filterConfig = $filterConfig;
    }

    public static function new(){
        return new CRUDTableBuilder();
    }

    public function render()
    {
        return view('tablesv2.crudtable', ['page' => $this]);
    }
}

class CRUDTableBuilder
{
    public string $title = '';
    public array $buttons = [];
    public ?Component $paginatorRowsSelector = null;
    public ?Component $searchBox = null;
    public ?TableComponent $tableComponent = null;
    public ?FilterConfig $filterConfig = null;

    public function title(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function addButton(Component $button): self
    {
        $this->buttons[] = $button;
        return $this;
    }

    public function buttons(array $buttons): self
    {
        $this->buttons = $buttons;
        return $this;
    }

    public function paginatorRowsSelector(?Component $paginatorRowsSelector): self
    {
        $this->paginatorRowsSelector = $paginatorRowsSelector;
        return $this;
    }

    public function searchBox(?Component $searchBox): self
    {
        $this->searchBox = $searchBox;
        return $this;
    }

    public function tableComponent(?TableComponent $tableComponent): self
    {
        $this->tableComponent = $tableComponent;
        return $this;
    }

    public function filterConfig(?FilterConfig $filterConfig): self
    {
        $this->filterConfig = $filterConfig;
        return $this;
    }

    public function build(): CRUDTableComponent
    {
        return new CRUDTableComponent(
            $this->title,
            $this->buttons,
            $this->paginatorRowsSelector,
            $this->searchBox,
            $this->tableComponent,
            $this->filterConfig,
        );
    }

    public function render(){
        return $this->build()->render();
    }
}