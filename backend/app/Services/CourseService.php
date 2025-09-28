<?php

namespace App\Services;

use App\Repositories\Contracts\CourseRepositoryInterface;

class CourseService
{
    protected CourseRepositoryInterface $repository;

    public function __construct(CourseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(array $queryParams)
    {
        return $this->repository->index($queryParams);
    }

    public function show(int $id)
    {
        return $this->repository->show($id);
    }

    public function store(array $data)
    {
        if ($data['image']) {
            $url = UploadFileService::uploadImages($data['image']);
            $data["image"] = $url['url'];
        }

        return $this->repository->store($data);
    }

    public function update(array $data)
    {
        if ($data['image']) {
            $url = UploadFileService::uploadImages($data['image']);
            $data["image"] = $url['url'];
        }

        return $this->repository->update($data);
    }


    public function byTeacher(array $queryParams)
    {
        return $this->repository->byTeacher($queryParams);
    }

    public function destroy(int $id)
    {
        return $this->repository->destroy($id);
    }
}
