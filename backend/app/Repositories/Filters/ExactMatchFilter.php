<?php

namespace App\Repositories\Filters;

use Illuminate\Database\Eloquent\Builder;

class ExactMatchFilter implements FilterInterface
{
    protected string $field;

    public function __construct(string $field)
    {
        $this->field = $field;
    }

    public function apply(Builder $query, mixed $value): Builder
    {
        return $query->where($this->field, $value);
    }
}
