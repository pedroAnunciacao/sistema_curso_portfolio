<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PessoaScope implements Scope
{
    public function apply(Builder $query, Model $model): void
    {
        if (!request()->pessoaId) {
            return;
        }

        $query->where('pessoa_id', request()->pessoaId);
    }
}
