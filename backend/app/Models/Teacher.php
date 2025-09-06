<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableInterface;

class Teacher extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['person_id', 'client_id'];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function transformAudit(array $data): array
    {
        $data['client_id'] = request()->client_id;
        return $data;
    }


    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
