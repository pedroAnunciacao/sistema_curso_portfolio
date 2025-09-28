<?php

namespace App\Repositories;

use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Filters\FilterResolver;
use App\Repositories\Filters\LikeNameFilter;

class UserRepository implements UserRepositoryInterface
{

    private $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function index(array $queryParams)
    {
        $perPage = (int) isset($queryParams['page_size']) ?? 10;

        $filters = [
            'name' => LikeNameFilter::class,
        ];

        $query = $this->model::query();

        $query = FilterResolver::applyFilters($query, $filters, $queryParams);

        $users = $query->paginate($perPage);
        return UserResource::collection($users);
    }

    public function show(mixed $userId)
    {
        $user = $this->model::findOrFail($userId);
        return new UserResource($user->load(['person']));
    }


    public function store(array $data)
    {
        $user = $this->model::create($data);
        return new UserResource($user);
    }

    public function update(array $data)
    {
        $user = $this->model::findOrFail($data['id']);
        $user->update($data);
        return new UserResource($user);
    }

    public function destroy(mixed $userId)
    {
        $user = $this->model::findOrFail($userId);
        $user->delete();
        return response()->noContent();
    }

        public function restore(int|string $id)
    {
        $user = $this->model->withTrashed()->findOrFail($id);
        $user->restore();
        return $user;
    }

}
