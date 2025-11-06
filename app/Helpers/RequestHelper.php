<?php

namespace App\Helpers;

use Illuminate\Http\Request;

class SearchParams
{
    public int $page;
    public int $showing;
    public ?string $search;
    public array $applied_filters;

    public function __construct(int $page, int $showing, ?string $search, array $applied_filters = [])
    {
        $this->page = $page;
        $this->showing = $showing;
        $this->search = $search;
        $this->applied_filters = $applied_filters;
    }
}

class RequestHelper
{
    public static function extractSearchParams(Request $request): SearchParams
    {
        $page = $request->input('page', 1);
        $showing = $request->input('showing', 10);
        $search = $request->input('search');
        $appliedFilters = json_decode($request->input('applied_filters', '[]'), true) ?? [];

        if (!is_numeric($page) || $page <= 0) $page = 1;
        if (!is_numeric($showing) || $showing <= 0) $showing = 10;

        return new SearchParams((int)$page, (int)$showing, $search, $appliedFilters);
    }
}
