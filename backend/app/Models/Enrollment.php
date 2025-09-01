<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pessoa_id',
        'course_id',
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
