<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Contracts\PersonRepositoryInterface;
use App\Enums\TypePersons;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use Illuminate\Support\Facades\DB;
use App\Repositories\Filters\FilterResolver;
use App\Repositories\Filters\LikeNameFilter;


class PersonRepository implements PersonRepositoryInterface
{
    /**
     * @var \Closure
     */
    protected $clientRepositoryFactory;
    /**
     * @var \Closure
     */
    protected $teacherRepositoryFactory;

    /**
     * @var \Closure
     */
    protected $studentRepositoryFactor;

    /**
     * @var \Closure
     */
    protected $contactRepositoryFactory;
    /**
     * @var \Closure
     */
    protected $addressRepositoryFactory;
    /**
     * @var \Closure
     */
    protected $userRepositoryFactory;


    /**
     * @var \Person
     */
    private $model;

    public function __construct(
        \Closure $clientRepositoryFactory,
        \Closure $teacherRepositoryFactory,
        \Closure $studentRepositoryFactor,
        \Closure $userRepositoryFactory,
        \Closure $addressRepositoryFactory,
        \Closure $contactRepositoryFactory,
        Person $model,

    ) {
        $this->model = $model;
        $this->clientRepositoryFactory = $clientRepositoryFactory;
        $this->teacherRepositoryFactory = $teacherRepositoryFactory;
        $this->studentRepositoryFactor = $studentRepositoryFactor;
        $this->addressRepositoryFactory = $addressRepositoryFactory;
        $this->contactRepositoryFactory = $contactRepositoryFactory;
        $this->userRepositoryFactory = $userRepositoryFactory;

    }

    protected function addressRepository(): AddressRepository
    {
        return call_user_func($this->addressRepositoryFactory);
    }

    protected function contactRepository(): ContactRepository
    {
        return call_user_func($this->contactRepositoryFactory);
    }

    protected function userRepository(): UserRepository
    {
        return call_user_func($this->userRepositoryFactory);
    }
    protected function clientRepository(): ClientRepository
    {
        return call_user_func($this->clientRepositoryFactory);
    }

    protected function teacherRepository(): TeacherRepository
    {
        return call_user_func($this->teacherRepositoryFactory);
    }
    protected function studentRepository(): StudentRepository
    {
        return call_user_func($this->studentRepositoryFactor);
    }

    public function index(array $queryParams)
    {

        $perPage = (int) isset($queryParams['page_size']) ?? 10;

        $filters = [
            'name' => LikeNameFilter::class,
        ];

        $query = $this->model::query();

        $query = FilterResolver::applyFilters($query, $filters, $queryParams);

        $persons = $query->with(['client', 'teacher', 'student'])->paginate();

        return $persons;
    }

    public function show(int|string $personId)
    {
        $person = $this->model::findOrFail($personId);
        return $person;
    }

    public function store(array $data)
    {

        $person = DB::transaction(function () use ($data) {
            $resource = $this->model->create($data);
            $dataPersonType = null;
            switch ($data['type']) {
                case TypePersons::CLIENT;
                    $dataPersonType = [
                        'person_id' => $resource->id,
                        'config' => json_encode($data['client'][0]['config'][0])
                    ];

                    $this->clientRepository()->store($dataPersonType);

                    break;

                case TypePersons::TEACHER;

                    $this->teacherRepository()->store(['person_id' => $resource->id]);
                    break;
                case TypePersons::STUDENT;

                    $dataPersonType = [
                        'person_id' => $resource->id,
                        'email_educacional' => $data['email_educacional']
                    ];
                    $this->studentRepository()->store($dataPersonType);
                    break;
            }

            $data['user'][0]['person_id'] = $resource->id;
            $this->userRepository()->store($data['user'][0]);

            if (!empty($data['addresses'])) {
                $this->addressRepository()->createMany($resource->id, $data['addresses']);
                $resource->load('addresses.city.state');
            }

            if (!empty($data['contacts'])) {
                $this->contactRepository()->createMany($resource->id, $data['contacts']);
            }

            return $resource;
        });

        match ($data['type']) {
            'client' => $person->load('client'),
            'teacher' => $person->load('teacher'),
            'student' => $person->load('student'),
            default => null
        };

        return $person;
    }

    public function update(array $data)
    {
        $person = $this->model::with(['addresses', 'contacts', 'user'])->findOrFail($data['id']);

        DB::transaction(function () use ($person, $data) {
            switch ($data['type']) {
                case TypePersons::CLIENT;

                    $this->clientRepository()->update($data['client'][0]);
                    break;

                case TypePersons::TEACHER;

                    break;
                case TypePersons::STUDENT;

                    $this->studentRepository()->update($data['student'][0]);
                    break;
            }

            $this->userRepository()->update($data['user'][0]);
            if (isset($data['addresses'])) {

                $this->addressRepository()->updateForPerson($person->id, $data['addresses']);
                $person->load('addresses.city.state', 'user');
            }

            if (isset($data['contacts'])) {
                $this->contactRepository()->updateForPerson($person->id, $data['contacts']);
            }

            $person->update($data);
        });

        match ($data['type']) {
            'client' => $person->load('client'),
            'teacher' => $person->load('teacher'),
            'student' => $person->load('student'),
            default => null
        };


        return $person;
    }

    public function destroy(int|string $personId)
    {
        $person = $this->model::findOrFail($personId);
        $person->delete();
        return response()->noContent();
    }

    public function restore(int|string $id)
    {
        $pessoa = $this->model::withTrashed()->findOrFail($id);
        $pessoa->restore();
        return $pessoa;
    }
}
