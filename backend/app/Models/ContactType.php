<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactType extends Model
{
    protected $table = 'contacts_types';

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;
}
