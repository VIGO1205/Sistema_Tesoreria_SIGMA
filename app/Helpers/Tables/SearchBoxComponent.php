<?php

namespace App\Helpers\Tables;

use App\Helpers\Tables\Component;

class SearchBoxComponent implements Component {
    public string $route;
    public ?string $placeholder = null;
    public ?string $value = null;
    
    public function __construct(string $route = "tablesv2.search.default_searchbox"){
        $this->route = $route;
    }

    public function render(){
        return view($this->route, ['page' => $this]);
    }
}