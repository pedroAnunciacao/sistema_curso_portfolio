<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableInterface;

class Contact extends Model implements AuditableInterface
{
    use Auditable;
    use HasFactory;


    protected $with = ['Type'];

    protected $fillable = ['content', 'contact_type_id'];

    public function transformAudit(array $data): array
    {
        $data['client_id'] = request()->client_id;
        return $data;
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function Type()
    {
        return $this->belongsTo(ContactType::class, 'contact_type_id');
    }
}
