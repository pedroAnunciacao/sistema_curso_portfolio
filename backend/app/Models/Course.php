<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToPessoa;

class Course extends Model
{
    use HasFactory, SoftDeletes, BelongsToPessoa;

    protected $fillable = [
        'title',
        'description',
        'pessoa_id',
    ];


    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function professor()
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id');
    }

    public function students()
    {
        return $this->belongsToMany(Pessoa::class, 'enrollments')->withTimestamps();
    }
}
