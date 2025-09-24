<?php

namespace App\Repositories\Filters;

use Illuminate\Database\Eloquent\Builder;

class RelationshipExactFilter implements FilterInterface
{
    protected string $relationshipPath;
    protected string $field;

    public function __construct(string $relationshipPath, string $field)
    {
        $this->relationshipPath = $relationshipPath;
        $this->field = $field;
    }

    public function apply(Builder $query, mixed $value): Builder
    {
        $relationships = explode('.', $this->relationshipPath);
        $finalField = array_pop($relationships);
        
        return $this->applyNestedWhereHas($query, $relationships, $finalField, $value);
    }

    protected function applyNestedWhereHas(Builder $query, array $relationships, string $field, mixed $value): Builder
    {
        if (empty($relationships)) {
            return $query->where($field, $value);
        }

        $currentRelation = array_shift($relationships);
        
        return $query->whereHas($currentRelation, function ($subQuery) use ($relationships, $field, $value) {
            $this->applyNestedWhereHas($subQuery, $relationships, $field, $value);
        });
    }
}
