<?php

namespace App\Repositories\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class LikeNameFilter implements FilterInterface
{
    protected string $field;

    public function __construct(string $field)
    {
        $this->field = $field;
    }

    public function apply(Builder $query, mixed $value): Builder
    {
        $value = strtolower($value);
        
        // Debug: Log the filter being applied
        Log::info("[Filter Debug] LikeNameFilter - Field: {$this->field}, Value: {$value}");
        
        return $query->whereRaw("LOWER(`{$this->field}`) LIKE ?", ["%{$value}%"]);
    }
}
