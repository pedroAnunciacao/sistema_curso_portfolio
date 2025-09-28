<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuditService;
use App\Http\Resources\Audit\AuditResource;

class AuditController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function index(Request $request)
    {
        $filters = $request->only([
            'user_id',
            'auditable_type',
            'auditable_id',
            'date_from',
            'date_to'
        ]);

        $audits = $this->auditService->getAll($filters);

        return AuditResource::collection($audits);
    }
}
