<?php

namespace App\Http\Resources\Audit;

use Illuminate\Http\Resources\Json\JsonResource;

class AuditResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'event'           => $this->event,
            'auditable_type'  => $this->auditable_type,
            'auditable_id'    => $this->auditable_id,
            'old_values'      => $this->old_values,
            'new_values'      => $this->new_values,
            'user_id'         => $this->user_id,
            'url'             => $this->url,
            'ip_address'      => $this->ip_address,
            'user_agent'      => $this->user_agent,
            'tags'            => $this->tags,
            'created_at'      => $this->created_at,
        ];
    }
}
