<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
        use SoftDeletes;

    protected $fillable = [
        'transaction_id',
        'method',
        'status',
        'model_type',
        'model_id',
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];

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
