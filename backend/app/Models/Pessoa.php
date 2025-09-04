<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pessoa extends Model
{
    use HasFactory, SoftDeletes;

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

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }


    public function getClienteIdAttribute()
    {
        if ($this->student) {
            return $this->student->cliente_id;
        }

        if ($this->teacher) {
            return $this->teacher->id;
        }

        if ($this->client) {
            return $this->client->id;
        }

        return null;
    }


    public static function getCustomColumns(): array
    {
        return [
            'nome',
            'email',
            'role',
        ];
    }
}
