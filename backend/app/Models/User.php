<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Passport\HasApiTokens;
use OwenIt\Auditing\Auditable;  
use OwenIt\Auditing\Contracts\Auditable as AuditableInterface;

use App\Traits\HasRoleIds;

class User extends Authenticatable implements AuditableInterface
{
    use HasApiTokens, HasFactory, Notifiable, HasRoleIds, Auditable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'person_id'
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

    public function transformAudit(array $data): array
    {
        $data['client_id'] = request()->client_id;
        return $data;
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function student()
    {
        return $this->person?->student;
    }

    public function teacher()
    {
        return $this->person?->teacher;
    }

    public function client()
    {
        return $this->person?->client;
    }
}
