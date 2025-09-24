<?php

namespace App\Http\Resources\Person;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Client\ClientResource;
use App\Http\Resources\Teacher\TeacherResource;
use App\Http\Resources\Student\StudentResource;
use App\Http\Resources\Addresses\AddressesResource;
class PersonResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user' => new UserResource($this->whenLoaded('user')),
            'client' => new ClientResource($this->whenLoaded('client')) ,
            'teacher' => new TeacherResource($this->whenLoaded('teacher')),
            'student' => new StudentResource($this->whenLoaded('student')),
            'Addresses' => new AddressesResource($this->whenLoaded('addresses')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
