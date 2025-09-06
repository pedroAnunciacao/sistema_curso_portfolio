<?php

declare(strict_types=1);

namespace App\Models;

use OwenIt\Auditing\Contracts\Auditable as AuditableInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;

class Address extends Model implements AuditableInterface
{
    use Auditable;
    use HasFactory;

    protected $table = 'addresses';

    protected $fillable = [
        'zip_code',
        'street',
        'number',
        'complement',
        'neighborhood',
        'default',
        'city_id',
    ];

    public function transformAudit(array $data): array
    {
        $data['client_id'] = request()->client_id;
        return $data;
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
