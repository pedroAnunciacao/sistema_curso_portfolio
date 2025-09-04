<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToPessoa;
use Stancl\VirtualColumn\VirtualColumn;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['pessoa_id', 'client_id'];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
