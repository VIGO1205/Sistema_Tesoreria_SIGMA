<?php

namespace App\Helpers\Tables;

class ViewBasedComponent implements Component {

    public string $route;
    public array $param;

    public function __construct(string $route, array $param = []){
        $this->route = $route;
        $this->param = $param;
    }
    public function render(){
        return view($this->route, $this->param);
    }
}