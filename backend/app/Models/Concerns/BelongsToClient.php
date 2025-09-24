<?php

namespace App\Models\Concerns;

use App\Models\Client;
use App\Scopes\ClientScope;

trait BelongsToClient
{
    public static function bootBelongsToClient(): void
    {
        static::addGlobalScope(new ClientScope());

        static::creating(function ($model): void {
            if (!$model->getAttribute('client_id') && !$model->relationLoaded('client_id')) {
                if (request()->client_id) {
                    $model->setAttribute('client_id', request()->client_id);
                }
            }
        });
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
