<?php

namespace App\Traits;

trait ResolveIlikeNameFilter
{
    /**
     * Recebe os filtros e aplica ILIKE nos campos de nome
     */
    protected function normalizeNameFilters(array $queryParams): array
    {
        foreach ($queryParams['includesFilter'] ?? [] as $relation => $filters) {
            if (isset($filters['name'])) {
                $queryParams['includesFilter'][$relation]['name'] = '%' . $filters['name'] . '%';
            }
        }

        return $queryParams;
    }
}
