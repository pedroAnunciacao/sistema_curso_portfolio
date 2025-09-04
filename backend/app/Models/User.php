<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Traits\HasRoleIds;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoleIds;

    protected $fillable = [
        'name',
        'email',
        'password',
        'pessoa_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function student()
    {
        return $this->pessoa?->student;
    }

    public function teacher()
    {
        return $this->pessoa?->teacher;
    }

    public function client()
    {
        return $this->pessoa?->client;
    }
}
