<?php

namespace App\Helpers\Tables;

use App\Helpers\Tables\Component;

class TableButtonComponent implements Component {
    public string $route;
    public ?array $action = null;
    
    public function __construct(string $route = "tablesv2.buttons.default", array $action = null){
        $this->route = $route;
        $this->action = $action;
    }
    
    public function render(){
        return view($this->route, ["page" => $this]);
    }
}