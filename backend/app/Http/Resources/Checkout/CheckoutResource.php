<?php

namespace App\Http\Resources\Checkout;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Teacher\TeacherResource;
use App\Http\Resources\Student\StudentResource;
class CheckoutResource extends JsonResource
{
    public function toArray($request): array
    {
        $activeGateway = app('client')->gateways('config.payments.integrations.useConfig.active');

        return [
            'id' => $this->id,
            'transaction_id' => $this->transaction_id,
            'method' => $this->method,
            'status' => $this->status,
            'model_type' => $this->model_type,
            'model_id' => $this->model_id,
            'teacher' => new TeacherResource($this->whenLoaded('teacher')),
            'student' => new StudentResource($this->whenLoaded('student')),
            'model' => $this->whenLoaded('model', fn() => $this->model ? $this->getPolymorphicResource($this->model) : null),
            $activeGateway => $this->data,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    protected function getPolymorphicResource($model)
    {
        $className = (new \ReflectionClass($model))->getShortName();
        $resourceClass = "\\App\\Http\\Resources\\{$className}Resource";

        if (class_exists($resourceClass)) {
            return new $resourceClass($model);
        }

        return $model->toArray();
    }
}
