<?php

namespace App\Repositories\Filters;

use Illuminate\Database\Eloquent\Builder;

class FilterResolver
{
    /**
     * Aplica filtros dinamicamente baseado na configuração
     * 
     * $filters = [
     *    'name' => LikeNameFilter::class,
     *    'teacher.person.name' => LikeNameFilter::class,
     * ];
     */
    public static function applyFilters(Builder $query, array $filters, array $params): Builder
    {
        foreach ($filters as $filterKey => $filterClass) {
            // Verifica se o parâmetro existe nos dados recebidos
            if (!isset($params[$filterKey]) || empty($params[$filterKey])) {
                continue;
            }

            $filterValue = $params[$filterKey];
            
            // Detecta se é um filtro de relacionamento (contém pontos)
            if (str_contains($filterKey, '.')) {
                $query = self::applyRelationshipFilter($query, $filterKey, $filterClass, $filterValue);
            } else {
                $query = self::applyDirectFilter($query, $filterKey, $filterClass, $filterValue);
            }
        }

        return $query;
    }

    /**
     * Aplica filtro direto no modelo principal
     */
    protected static function applyDirectFilter(Builder $query, string $field, string $filterClass, mixed $value): Builder
    {
        $filter = new $filterClass($field);
        return $filter->apply($query, $value);
    }

    /**
     * Aplica filtro em relacionamento usando whereHas
     */
    protected static function applyRelationshipFilter(Builder $query, string $relationshipPath, string $filterClass, mixed $value): Builder
    {
        // Separa o caminho do relacionamento do campo final
        $parts = explode('.', $relationshipPath);
        $field = array_pop($parts);
        $relationshipPath = implode('.', $parts);

        // Usa o RelationshipLikeFilter para lidar com relacionamentos
        if ($filterClass === LikeNameFilter::class) {
            $filter = new RelationshipLikeFilter($relationshipPath, $field);
            return $filter->apply($query, $value);
        }

        // Para outros tipos de filtro, você pode adicionar mais lógica aqui
        // Por enquanto, usa o RelationshipLikeFilter como padrão
        $filter = new RelationshipLikeFilter($relationshipPath, $field);
        return $filter->apply($query, $value);
    }
}
