<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pessoa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'cpf',
        'data_nascimento',
        'email'
    ];

    // Relacionamentos
    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function enderecos()
    {
        return $this->hasMany(Endereco::class);
    }

    public function contatos()
    {
        return $this->hasMany(Contato::class);
    }
}
