<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'email_educacional' => $this->email_educacional,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
