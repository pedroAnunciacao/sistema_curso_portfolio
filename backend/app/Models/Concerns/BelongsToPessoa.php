<?php

namespace App\Models\Concerns;

use App\Models\Pessoas;
use App\Scopes\PessoaScope;

trait BelongsToPessoa
{
    public static function bootBelongsToPessoa(): void
    {
        static::addGlobalScope(new PessoaScope());

        static::creating(function ($model): void {
            if (!$model->getAttribute('pessoa_id') && !$model->relationLoaded('pessoa')) {
                if (request()->pessoaId) {
                    $model->setAttribute('pessoa_id', request()->pessoaId);
                }
            }
        });
    }

    public function pessoa()
    {
        return $this->belongsTo(\App\Models\Pessoa::class);
    }
}
