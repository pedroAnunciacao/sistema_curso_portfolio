<?php

namespace App\Repositories\Includes;

use Illuminate\Database\Eloquent\Builder;

interface IncludesInterface
{
    public static function applyIcludes(Builder $query, string $includes): Builder;
}
