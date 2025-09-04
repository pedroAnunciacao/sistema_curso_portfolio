<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\BelongsToPessoa;
use Stancl\VirtualColumn\VirtualColumn;

class Pessoa extends Model
{
    use HasFactory, SoftDeletes, BelongsToPessoa, VirtualColumn;

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



    public function teacher()
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id')->where('role', 'teacher');
    }


    public function client()
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id')->where('role', 'client');
    }


    public function aluno()
    {
        return $this->hasMany(Pessoa::class, 'pessoa_id')->where('role', 'student');
    }
    public function resolveConfig(): array
    {
        if ($this->role === 'teacher' && $this->client) {
            return $this->client->config ?? [];
        }

        if ($this->role === 'student' && $this->teacher && $this->teacher->client) {
            return $this->teacher->client->config ?? [];
        }

        return [];
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
