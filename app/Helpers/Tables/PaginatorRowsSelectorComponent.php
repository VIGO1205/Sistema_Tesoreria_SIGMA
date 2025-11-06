<?php

namespace App\Helpers\Tables;

use App\Helpers\Tables\Component;

class PaginatorRowsSelectorComponent implements Component {
    public string $route;
    public array $options;
    public $valueSelected = null;
    
    public function __construct(array $options = [10, 8, 5], string $route = "tablesv2.paginator.default_paginator_rows_selector"){
        $this->route = $route;
        $this->options = $options;
    }
    
    public function render(){
        return view($this->route, ['page' => $this]);
    }
}