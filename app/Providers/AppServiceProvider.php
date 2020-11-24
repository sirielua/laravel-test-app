<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Components
use App\domain\dispatchers\EventDispatcher;
use App\domain\dispatchers\DummyEventDispatcher;
use App\domain\repositories\Hydrator;

// Repositories
use App\domain\repositories\Contest\ContestRepository;
use App\repositories\EloquentContestRepository;

// Services

// Contest Services
use App\domain\service\Contest\Activate\ActivateHandler;
use App\domain\service\Contest\Create\CreateHandler;
use App\domain\service\Contest\Deactivate\DeactivateHandler;
use App\domain\service\Contest\Remove\RemoveHandler;
use App\domain\service\Contest\Update\UpdateHandler;

//Participant Services

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerComponents();
        $this->registerRepositories();
        $this->registerServices();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    private function registerComponents()
    {
        $this->app->singleton(EventDispatcher::class, function ($app) {
            return new DummyEventDispatcher();
        });

        $this->app->singleton(Hydrator::class, function ($app) {
            return new Hydrator();
        });
    }

    private function registerRepositories()
    {
        $this->app->singleton(ContestRepository::class, function ($app) {
            return new EloquentContestRepository($app->make(Hydrator::class));
        });
    }

    private function registerServices()
    {
        $this->registerContestHandlers();
        $this->registerParticipantHandlers();
    }

    private function registerContestHandlers()
    {
        $this->app->bind(ActivateHandler::class, function ($app) {
            return new ActivateHandler($app->make(ContestRepository::class), $app->make(EventDispatcher::class));
        });

        $this->app->bind(CreateHandler::class, function ($app) {
            return new CreateHandler($app->make(ContestRepository::class), $app->make(EventDispatcher::class));
        });

        $this->app->bind(DeactivateHandler::class, function ($app) {
            return new DeactivateHandler($app->make(ContestRepository::class), $app->make(EventDispatcher::class));
        });

        $this->app->bind(RemoveHandler::class, function ($app) {
            return new RemoveHandler($app->make(ContestRepository::class), $app->make(EventDispatcher::class));
        });

        $this->app->bind(UpdateHandler::class, function ($app) {
            return new UpdateHandler($app->make(ContestRepository::class), $app->make(EventDispatcher::class));
        });
    }

    private function registerParticipantHandlers()
    {

    }
}
