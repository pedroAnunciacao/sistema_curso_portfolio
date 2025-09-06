<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckoutResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'transaction_id' => $this->transaction_id,
            'method' => $this->method,
            'status' => $this->status,
            'model_type' => $this->model_type,
            'model' => $this->whenLoaded('model', function () {
                return $this->resource->model
                    ? $this->getPolymorphicResource($this->resource->model)
                    : null;
            }),
            'data' => $this->data,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    protected function getPolymorphicResource($model)
    {
        // Pega o name da classe sem namespace
        $className = (new \ReflectionClass($model))->getShortName();

        // Converte para PascalCase singular (por convenção)
        $resourceClass = "\\App\\Http\\Resources\\{$className}Resource";

        if (class_exists($resourceClass)) {
            return new $resourceClass($model);
        }

        // fallback: retorna array cru do model
        return $model->toArray();
    }
}
