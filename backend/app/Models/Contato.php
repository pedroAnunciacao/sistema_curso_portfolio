<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contato extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pessoa_id',
        'tipo',
        'valor',
        'principal',
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }
}
