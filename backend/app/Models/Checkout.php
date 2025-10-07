<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableInterface;
use App\Models\Concerns\BelongsToTeacher;
use App\Models\Concerns\BelongsToClient;
use App\Models\Concerns\BelongsToStudent;
use App\Events\CheckoutCreated;

class Checkout extends Model implements AuditableInterface
{
    use SoftDeletes, Auditable, HasFactory, BelongsToClient, BelongsToTeacher, BelongsToStudent;

        protected $dispatchesEvents = [
        'created' => CheckoutCreated::class,
    ];

    protected $fillable = [
        'transaction_id',
        'method',
        'status',
        'model_type',
        'model_id',
        'data',
        'teacher_id',
        'student_id'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    public function transformAudit(array $data): array
    {
        $data['client_id'] = request()->client_id;
        return $data;
    }

    public function model()
    {
        return $this->morphTo();
    }

    public function toArray()
    {
        $array = parent::toArray();

        if ($this->relationLoaded('model') && $this->model) {
            $array[strtolower(class_basename($this->model_type))] = $this->model->toArray();
            unset($array['model']);
        }

        return $array;
    }
}
