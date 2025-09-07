<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableInterface;

class Person extends Model implements AuditableInterface
{
    use HasFactory, SoftDeletes, Auditable;

        protected $fillable = ['name', 'email', 'birth_date', 'cpf_cnpj'];


    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function addresses()
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
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

    public function transformAudit(array $data): array
    {
        $data['client_id'] = request()->client_id;
        return $data;
    }


    public function getClienteIdAttribute()
    {
        if ($this->student) {
            return $this->student->client_id;
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
            'name',
            'email',
            'cpf_cnpj',
            'birth_date',

        ];
    }
}
