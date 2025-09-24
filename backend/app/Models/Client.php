<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableInterface;
use Stancl\VirtualColumn\VirtualColumn;

class Client extends Model implements AuditableInterface
{
    use HasFactory, SoftDeletes, Auditable, HasFactory;

    protected $fillable = ['person_id', 'config'];

        protected $casts = [
        'data' => 'array'
        ];

        public function person()
        {
            return $this->belongsTo(Person::class);
        }
}
