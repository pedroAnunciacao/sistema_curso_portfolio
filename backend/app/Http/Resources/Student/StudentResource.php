<?php

namespace App\Http\Resources\Student;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Person\PersonResource;

class StudentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'email_educacional' => $this->email_educacional,
            'person' => new PersonResource($this->whenLoaded('person')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
