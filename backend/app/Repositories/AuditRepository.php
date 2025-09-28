<?php

namespace App\Repositories;

use OwenIt\Auditing\Models\Audit;
use App\Repositories\Contracts\AuditRepositoryInterface;
use App\Repositories\Filters\ExactMatchFilter;
use App\Repositories\Filters\FilterResolver;

class AuditRepository implements AuditRepositoryInterface
{
    public function index(array $queryParams)
    {
        $query = Audit::query();


        $filters = [
            'user_id' => ExactMatchFilter::class,
            'auditable_type' => ExactMatchFilter::class,
            'auditable_id' => ExactMatchFilter::class,
            'date_from' => ExactMatchFilter::class,
            'date_to' => ExactMatchFilter::class,

        ];

        $query = FilterResolver::applyFilters($query, $filters, $queryParams);



        return $query->latest()->get();
    }
}
