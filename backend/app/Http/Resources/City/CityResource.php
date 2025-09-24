<?php

declare(strict_types=1);

namespace App\Http\Resources\City;
use App\Http\Resources\State\StateResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->when($this->codigo, $this->codigo),
            'state' => new StateResource($this->whenLoaded('state')),
        ];
    }
}
