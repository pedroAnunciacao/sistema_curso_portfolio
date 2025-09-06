<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EstadoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'codigo' => $this->codigo,
            'uf' => $this->uf,
            'ddd' => $this->ddd,
            'cidades' => CidadeResource::collection($this->whenLoaded('cidades')),
        ];
    }
}
