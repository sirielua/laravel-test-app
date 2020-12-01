<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Components
use App\domain\dispatchers\EventDispatcher;
use App\domain\dispatchers\DummyEventDispatcher;
use App\domain\repositories\Hydrator;

use App\domain\components\ConfirmationCodeGenerator\ConfirmationCodeGenerator;
use App\domain\components\ConfirmationCodeGenerator\NumberConfirmationCodeGenerator;

// Repositories
use App\domain\repositories\Contest\ContestRepository;
use App\repositories\EloquentContestRepository;
use App\domain\repositories\Participant\ParticipantRepository;
use App\repositories\EloquentParticipantRepository;

// Services

// Contest Services
use App\domain\service\Contest\Activate\ActivateHandler as ContestActivateHandler;
use App\domain\service\Contest\Create\CreateHandler as ContestCreateHandler;
use App\domain\service\Contest\Deactivate\DeactivateHandler as ContestDeactivateHandler;
use App\domain\service\Contest\Remove\RemoveHandler as ContestRemoveHandler;
use App\domain\service\Contest\Update\UpdateHandler as ContestUpdateHandler;

//Participant Services
use App\domain\service\Participant\Register\RegisterHandler as ParticipantRegisterHandler;

class DomainServiceProvider extends ServiceProvider
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

        $this->app->singleton(ConfirmationCodeGenerator::class, function ($app) {
            return new NumberConfirmationCodeGenerator($length = 4);
        });
    }

    private function registerRepositories()
    {
        $this->app->singleton(ContestRepository::class, function ($app) {
            return new EloquentContestRepository($app->make(Hydrator::class));
        });

        $this->app->singleton(ParticipantRepository::class, function ($app) {
            return new EloquentParticipantRepository($app->make(Hydrator::class));
        });
    }

    private function registerServices()
    {
        $this->registerContestHandlers();
        $this->registerParticipantHandlers();
    }

    private function registerContestHandlers()
    {
        $this->app->bind(ContestActivateHandler::class, function ($app) {
            return new ContestActivateHandler($app->make(ContestRepository::class), $app->make(EventDispatcher::class));
        });

        $this->app->bind(ContestCreateHandler::class, function ($app) {
            return new ContestCreateHandler($app->make(ContestRepository::class), $app->make(EventDispatcher::class));
        });

        $this->app->bind(ContestDeactivateHandler::class, function ($app) {
            return new ContestDeactivateHandler($app->make(ContestRepository::class), $app->make(EventDispatcher::class));
        });

        $this->app->bind(ContestRemoveHandler::class, function ($app) {
            return new ContestRemoveHandler($app->make(ContestRepository::class), $app->make(EventDispatcher::class));
        });

        $this->app->bind(ContestUpdateHandler::class, function ($app) {
            return new ContestUpdateHandler($app->make(ContestRepository::class), $app->make(EventDispatcher::class));
        });
    }

    private function registerParticipantHandlers()
    {
        $this->app->bind(ParticipantRegisterHandler::class, function ($app) {
            return new ParticipantRegisterHandler(
                $app->make(ConfirmationCodeGenerator::class),
                $app->make(ParticipantRepository::class),
                $app->make(EventDispatcher::class),
            );
        });


    }
}
