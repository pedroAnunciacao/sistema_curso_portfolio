<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $casts = [
        'ddd' => 'array',
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
