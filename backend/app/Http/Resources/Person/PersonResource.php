<?php

namespace App\Http\Resources\Person;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Client\ClientResource;
use App\Http\Resources\Teacher\TeacherResource;
use App\Http\Resources\Student\StudentResource;
use App\Http\Resources\Addresses\AddressesResource;
use Mockery\Undefined;

class PersonResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user' => new UserResource($this->whenLoaded('user')),
            'client' => $this->when(
                $this->client !== null,
                new ClientResource($this->client)
            ),
            'teacher' => $this->when(
                $this->teacher !== null,
                new TeacherResource($this->teacher)
            ),

            'student' => $this->when(
                $this->student !== null,
                new StudentResource($this->student)
            ),

            'Addresses' => new AddressesResource($this->whenLoaded('addresses')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
