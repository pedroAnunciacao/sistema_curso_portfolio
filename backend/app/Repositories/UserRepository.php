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

        $filters = [
            'title' => LikeNameFilter::class,
            'teacher.person.name' => LikeNameFilter::class,
        ];

        $query = $this->model::query();

        $query = FilterResolver::applyFilters($query, $filters, $queryParams);

        $users = $query->with(['teacher.person', 'lessons'])->paginate(10);
        return UserResource::collection($users);
    }

    public function show(mixed $userId)
    {
        $user = $this->model::findOrFail($userId);
        return new UserResource($user->load(['teacher.person', 'lessons', 'students']));
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
}
