<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FilteredSearchQuery {
    private static function defineGeneralSearch(Builder $query, array $sqlColumns, $search) {
        $query->where(function($q) use ($sqlColumns, $search) {
            foreach ($sqlColumns as $col) {
                if (strpos($col, '.') !== false) {
                    [$relation, $relatedColumn] = explode('.', $col, 2);
                    $q->orWhereHas($relation, function($qr) use ($relatedColumn, $search) {
                        $qr->where($relatedColumn, 'LIKE', "%{$search}%");
                    });
                } else {
                    $q->orWhere($col, 'LIKE', "%{$search}%");
                }
            }
        });
    }

    public static function fromQuery($query, array $sqlColumns, $generalSearch = null, array $filters = [], array $filterToColumnMap = []){
        if ($generalSearch) {
            static::defineGeneralSearch($query, $sqlColumns, $generalSearch);
        }

        foreach ($filters as $filter) {
            $columnName = $filter['key'];
            $value = $filter['value'];
            $dbColumn = $filterToColumnMap[$columnName] ?? strtolower($columnName);

            if (strpos($dbColumn, '.') !== false) {
                [$relation, $relatedColumn] = explode('.', $dbColumn, 2);
                $query->whereHas($relation, function($q) use ($relatedColumn, $value) {
                    if (is_numeric($value)) {
                        $q->where($relatedColumn, '=', $value);
                    } else {
                        $q->where($relatedColumn, 'LIKE', "%{$value}%");
                    }
                });
            } else {
                if (is_numeric($value)){
                    $query->where($dbColumn, '=', $value);
                } else {
                    $query->where($dbColumn, 'LIKE', "%{$value}%");
                }
            }
        }

        return $query;
    }

    public static function fromModel(Model $modelClass, array $sqlColumns, $generalSearch = null, array $filters = [], array $filterToColumnMap = []) {
        $query = $modelClass::query();

        return static::fromQuery($query, $sqlColumns, $generalSearch, $filters, $filterToColumnMap);
    }
}