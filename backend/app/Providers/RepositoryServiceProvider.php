<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\ContactRepositoryInterface;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\AddressRepositoryInterface;
use App\Repositories\Contracts\PersonRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\ClientRepositoryInterface;
use App\Repositories\Contracts\TeacherRepositoryInterface;
use App\Repositories\Contracts\StudentRepositoryInterface;

use App\Repositories\PersonRepository;
use App\Repositories\ClientRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\StudentRepository;
use App\Repositories\UserRepository;

use App\Repositories\AddressRepository;
use App\Repositories\ContactRepository;
use App\Repositories\CourseRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(ContactRepositoryInterface::class, ContactRepository::class);
        $this->app->bind(AddressRepositoryInterface::class, AddressRepository::class);
        $this->app->bind(ClientRepositoryInterface::class, ClientRepository::class);
        $this->app->bind(TeacherRepositoryInterface::class, TeacherRepository::class);
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(ContactRepositoryInterface::class, ContactRepository::class);

        $this->app->bind(PersonRepositoryInterface::class, function ($app) {
            return new PersonRepository(
                fn() => $app->make(ClientRepositoryInterface::class),
                fn() => $app->make(TeacherRepositoryInterface::class),
                fn() => $app->make(StudentRepositoryInterface::class),
                fn() => $app->make(UserRepositoryInterface::class),
                fn() => $app->make(AddressRepositoryInterface::class),
                fn() => $app->make(ContactRepositoryInterface::class),
                $app->make(\App\Models\Person::class),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
