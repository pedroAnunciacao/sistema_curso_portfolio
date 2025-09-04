<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler;
use App\Exceptions\Handler as AppHandler;
use App\Services\ClientService;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ExceptionHandler::class, AppHandler::class);
            $this->app->scoped('client', function ($app) {
        return new ClientService();
    });

    }

    /**
     * Bootstrap any application services.
     */
public function boot(): void
{

    Relation::morphMap([
    'courses' => \App\Models\Course::class,
]);

    if (auth()->check()) {
        $client = $this->app->make('client');
        $client->loadConfig(auth()->user()->pessoa_id ?? 6); // fallback
    }
}
}
