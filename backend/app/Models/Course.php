<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableInterface;

use App\Models\Concerns\BelongsToTeacher;

class Course extends Model implements AuditableInterface
{
    use HasFactory, SoftDeletes, BelongsToTeacher, Auditable, HasFactory;

    protected $fillable = [
        'title',
        'description',
        'teacher_id',
    ];

    public function transformAudit(array $data): array
    {
        $data['client_id'] = request()->client_id;
        return $data;
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments')->withTimestamps();
    }
}
