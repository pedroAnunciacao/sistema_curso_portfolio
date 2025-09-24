<?php

namespace App\Repositories\Filters;
use Illuminate\Support\Facades\Log;

use Illuminate\Database\Eloquent\Builder;

class RelationshipLikeFilter implements FilterInterface
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
        $value = strtolower($value);
        
        Log::info("[Filter Debug] RelationshipLikeFilter - Path: {$this->relationshipPath}, Field: {$this->field}, Value: {$value}");
        
        // Para "teacher.person" como relationshipPath e "name" como field
        $relationships = explode('.', $this->relationshipPath);
        
        return $this->applyNestedWhereHas($query, $relationships, $this->field, $value);
    }

    protected function applyNestedWhereHas(Builder $query, array $relationships, string $field, mixed $value): Builder
    {
        if (empty($relationships)) {
            Log::info("[Filter Debug] Final WHERE - Field: {$field}, Value: {$value}");
            return $query->whereRaw("LOWER(`{$field}`) LIKE ?", ["%{$value}%"]);
        }

        $currentRelation = array_shift($relationships);
        
        Log::info("[Filter Debug] WhereHas - Relation: {$currentRelation}, Remaining: " . implode('.', $relationships));
        
        return $query->whereHas($currentRelation, function ($subQuery) use ($relationships, $field, $value) {
            return $this->applyNestedWhereHas($subQuery, $relationships, $field, $value);
        });
    }
}
