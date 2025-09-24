<?php

declare(strict_types=1);

namespace App\Http\Resources\State;
use App\Http\Resources\City\CityResource;

use Illuminate\Http\Resources\Json\JsonResource;

class StateResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'abbreviation' => $this->abbreviation,
            'area_codes' => $this->area_codes,
            'city' => CityResource::collection($this->whenLoaded('city')),
        ];
    }
}
