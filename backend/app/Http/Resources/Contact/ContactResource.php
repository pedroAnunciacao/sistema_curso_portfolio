<?php

declare(strict_types=1);

namespace App\Http\Resources\Contact;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'type' => $this->type->name,
            'conteudo' => $this->content,
        ];
    }
}
