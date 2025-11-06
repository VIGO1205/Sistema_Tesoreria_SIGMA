<?php

namespace App\Helpers\Tables;

use App\Helpers\Tables\Component;

class TableComponent implements Component
{
    public array $columns = [];
    public array $rows = [];
    public array $actions = [];
    public ?Component $paginator = null;
    public string $route = 'tablesv2.table.default_table';

    public function __construct(array $columns = [], array $rows = [], array $actions = [], ?Component $paginator = null, string $route = 'tablesv2.table.default_table')
    {
        $this->columns = $columns;
        $this->rows = $rows;
        $this->actions = $actions;
        $this->paginator = $paginator;
        $this->route = $route;
    }

    public function render()
    {
        return view($this->route, ["page" => $this]);
    }
}
