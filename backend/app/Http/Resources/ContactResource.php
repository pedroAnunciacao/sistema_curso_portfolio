<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'contatoTipoId' => $this->contato_tipo_id,
            'tipo' => $this->tipo->nome,
            'conteudo' => $this->conteudo,
        ];
    }
}
