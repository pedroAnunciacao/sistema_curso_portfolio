<?php

declare(strict_types=1);

namespace App\Http\Resources\Addresses;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\City\CityResource;
class AddressesResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'zip_code' => $this->zip_code,
            'street' => $this->street,
            'number' => $this->number,
            'number' => $this->number,
            'neighborhood' => $this->neighborhood,
            'complement' => $this->complement,
            'city' => new CityResource($this->whenLoaded('city')),
        ];
    }
}
