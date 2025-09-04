<?php

namespace App\Repositories;

use App\Http\Resources\CheckoutResource;
use App\Models\Checkout;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class CheckoutRepository
{
    protected $model;

    public function __construct(Checkout $model)
    {
        $this->model = $model;
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->get('page_size') ?? 10;

        $courses = QueryBuilder::for($this->model->query())
            ->defaultSort('-id')
            ->allowedFilters([
                AllowedFilter::exact('transaction_id'),
                AllowedFilter::exact('method'),
                AllowedFilter::exact('status'),
            ])
            ->allowedIncludes(['model'])
            ->withTrashed()
            ->paginate($perPage);

        return CheckoutResource::collection($courses);
    }

    public function create(array $data): Checkout
    {
        return $this->model->create($data);
    }

    public function findByTransactionId(string $transactionId): ?Checkout
    {
        return $this->model->where('transaction_id', $transactionId)->first();
    }

    public function updateStatus(string $transactionId, string $status, array $data = []): bool
    {
        $checkout = $this->findByTransactionId($transactionId);

        if (! $checkout) {
            return false;
        }

        return $checkout->update([
            'status' => $status,
            'data'   => array_merge($checkout->data ?? [], $data)
        ]);
    }
}
