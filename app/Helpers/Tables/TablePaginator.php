<?php

namespace App\Helpers\Tables;

use App\Helpers\Tables\Component;

class TablePaginator implements Component
{
    public int $currentPage;
    public int $totalPages;
    public array $queryParams;

    public function __construct(int $currentPage, int $totalPages, array $queryParams = [])
    {
        $this->currentPage = $currentPage;
        $this->totalPages = $totalPages;
        $this->queryParams = $queryParams;
    }

    public function render()
    {
        return view('components.pagination', [
            'currentPage' => $this->currentPage,
            'totalPages' => $this->totalPages,
            'queryParams' => $this->queryParams,
        ]);
    }
}
