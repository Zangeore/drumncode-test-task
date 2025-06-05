<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\Filters\Filter;

class FullTextSearchFilterWithRank implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        $value = trim($value);
        $connection = DB::connection();
        $grammar = $connection->getQueryGrammar();
        $searchVector = "to_tsvector('english', $property)";
        $searchQuery = $grammar->substituteBindingsIntoRawSql("plainto_tsquery('english', ?)", $connection->prepareBindings([$value]));
        $query->orderByRaw("ts_rank({$searchVector}, {$searchQuery}) desc")->limit(10);
    }
}
