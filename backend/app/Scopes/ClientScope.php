<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ClientScope implements Scope
{
    public function apply(Builder $query, Model $model): void
    {
        if (!request()->client_id) {
            return;
        }

        $query->where('client_id', request()->client_id);
    }
}
