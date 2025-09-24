<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableInterface;


class Lesson extends Model implements AuditableInterface
{
    use HasFactory, SoftDeletes, Auditable;

    protected $fillable = [
        'title',
        'content',
        'course_id',
        'image',
        "link_youtube"

    ];

    public function transformAudit(array $data): array
    {
        $data['client_id'] = request()->client_id;
        return $data;
    }


    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
