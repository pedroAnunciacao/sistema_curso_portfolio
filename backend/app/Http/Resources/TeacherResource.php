<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->person->id,
            'name' => $this->person->name,
            'email' => $this->person->email ?? null,
            'created_at' => $this->person->created_at,
            'updated_at' => $this->person->updated_at,
        ];
    }
}
