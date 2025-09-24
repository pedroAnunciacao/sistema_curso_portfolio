<?php

namespace App\Repositories\Includes;

use Illuminate\Database\Eloquent\Builder;

class Includes implements IncludesInterface
{
    /**
     * Aplica includes dinamicamente baseado na configuração
     * 
     */
    public static function applyIcludes(Builder $query, string $includes): Builder
    {
        $includesExplode = explode(',', $includes);

        foreach ($includesExplode as $include) {
            $query = $query->with($include);
        }
        
        return $query;
    }
}
