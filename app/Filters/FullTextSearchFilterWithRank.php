<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\Filters\Filter;

class FullTextSearchFilterWithRank implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        $value = trim((string) $value);
        $connection = DB::connection();
        $grammar = $connection->getQueryGrammar();
        $searchVector = sprintf("to_tsvector('english', %s)", $property);
        $searchQuery = $grammar->substituteBindingsIntoRawSql("plainto_tsquery('english', ?)", $connection->prepareBindings([$value]));
        $query->orderByRaw(sprintf('ts_rank(%s, %s) desc', $searchVector, $searchQuery))->limit(10);
    }
}
