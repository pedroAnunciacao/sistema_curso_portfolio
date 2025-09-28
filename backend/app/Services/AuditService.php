<?php

namespace App\Services;

use App\Repositories\Contracts\AuditRepositoryInterface;

class AuditService
{
    protected $auditRepository;

    public function __construct(AuditRepositoryInterface $auditRepository)
    {
        $this->auditRepository = $auditRepository;
    }

    public function getAll(array $filters = [])
    {
        return $this->auditRepository->index($filters);
    }
}
