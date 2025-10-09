<?php

namespace App\Repositories;

use App\Models\Checkout;
use App\Repositories\Filters\FilterResolver;
use App\Repositories\Filters\ExactMatchFilter;
use App\Repositories\Includes\Includes;
use App\Repositories\Contracts\CheckoutRepositoryInterface;

class CheckoutRepository implements CheckoutRepositoryInterface
{
    protected $model;

    public function __construct(Checkout $model)
    {
        $this->model = $model;
    }

    public function index(array $queryParams)
    {

        $query = $this->model::query();
        $perPage = (int) isset($queryParams['page_size']) ?? 10;
        $filters = [
            'client_id' => ExactMatchFilter::class,
            'teacher_id' => ExactMatchFilter::class,
            'student_id' => ExactMatchFilter::class,
            'transaction_id' => ExactMatchFilter::class,
            'model_id' => ExactMatchFilter::class,
            'model_type' => ExactMatchFilter::class,
            'method' => ExactMatchFilter::class,
            'status' => ExactMatchFilter::class,

        ];

        $query =  !empty($queryParams['includes']) ? Includes::applyIcludes($query, $queryParams['includes']) : $query;
        $query = FilterResolver::applyFilters($query, $filters, $queryParams);

        $checkouts = $query->paginate($perPage);

        return $checkouts;
    }

    public function show(int|string $id)
    {
        $checkout = $this->model->findOrFail($id);
        return $checkout->load(['model']);
    }

    public function store(array $data)
    {
        $checkout = $this->model->create($data);
        return $checkout;
    }

    public function update(array $data)
    {
        $checkout = $this->model->findOrFail($data['id']);
        $checkout->update($data);
        return $checkout;
    }

    public function destroy(int|string $id)
    {
        $checkout = $this->model->findOrFail($id);
        $checkout->delete();
        return response()->noContent();
    }

    public function restore(int|string $id)
    {
        $pessoa = $this->model->withTrashed()->findOrFail($id);
        $pessoa->restore();
        return $pessoa;
    }

    public function findByTransactionId(string $transactionId)
    {
        return $this->model->where('transaction_id', $transactionId)->first();
    }
}
