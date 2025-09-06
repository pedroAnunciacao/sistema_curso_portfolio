<?php

namespace App\Models\Concerns;

trait AuditWithClient
{
    /**
     * Adiciona client_id no registro de auditoria.
     * Recebe os dados já transformados pelo pacote.
     */
    public function addClientIdToAudit(array $data): array
    {
        $data['client_id'] = request()->client_id ?? null;
        return $data;
    }
}
