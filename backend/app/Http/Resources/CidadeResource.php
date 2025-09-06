<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CidadeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'codigo' => $this->when($this->codigo, $this->codigo),
            'estadoId' => $this->when($this->estado_id, $this->estado_id),
            'estado' => new EstadoResource($this->whenLoaded('estado')),
        ];
    }
}
