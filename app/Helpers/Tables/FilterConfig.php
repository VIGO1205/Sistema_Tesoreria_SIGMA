<?php

namespace App\Helpers\Tables;

class FilterConfig {
    public array $filters = [];
    public array $filterOptions = [];

    public function __construct(array $filters = [], array $filterOptions = []){
        $this->filters = $filters;
        $this->filterOptions = $filterOptions;
    }
}