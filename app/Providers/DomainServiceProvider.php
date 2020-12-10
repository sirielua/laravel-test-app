<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Http\Request;

// Components
use App\domain\dispatchers\EventDispatcher;
use App\domain\dispatchers\DummyEventDispatcher;
use App\domain\repositories\Hydrator;

use App\domain\components\ConfirmationCodeGenerator\ConfirmationCodeGenerator;
use App\domain\components\ConfirmationCodeGenerator\NumberConfirmationCodeGenerator;
use App\domain\components\RegistrationNotifier\RegistrationNotifier;
use App\Components\SessionRegistrationNotifier;

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
use App\Components\ParticipantEventDispatcher;
use App\domain\service\Participant\Register\RegisterHandler as ParticipantRegisterHandler;
use App\domain\service\Participant\SendConfirmation\SendConfirmationHandler as ParticipantSendConfirmationHandler;
use App\domain\service\Participant\Remove\RemoveHandler as ParticipantRemoveHandler;
use App\domain\service\Participant\ConfirmRegistration\ConfirmRegistrationHandler as ParticipantConfirmRegistrationHandler;
use App\domain\service\Participant\UpdateReferralQuantity\UpdateReferralQuantityHandler as ParticipantUpdateReferralQuantityHandler;

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

        $this->app->singleton(RegistrationNotifier::class, function ($app) {
            return new SessionRegistrationNotifier($app->make(Request::class));
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
                $app->make(ParticipantEventDispatcher::class),
            );
        });

        $this->app->bind(ParticipantSendConfirmationHandler::class, function ($app) {
            return new ParticipantSendConfirmationHandler(
                $app->make(RegistrationNotifier::class),
                $app->make(ParticipantRepository::class),
                $app->make(ParticipantEventDispatcher::class),
            );
        });

        $this->app->bind(ParticipantRemoveHandler::class, function ($app) {
            return new ParticipantRemoveHandler(
                $app->make(ParticipantRepository::class),
                $app->make(ParticipantEventDispatcher::class),
            );
        });

        $this->app->bind(ParticipantConfirmRegistrationHandler::class, function ($app) {
            return new ParticipantConfirmRegistrationHandler(
                $app->make(ParticipantRepository::class),
                $app->make(ParticipantEventDispatcher::class),
            );
        });

        $this->app->bind(ParticipantUpdateReferralQuantityHandler::class, function ($app) {
            return new ParticipantUpdateReferralQuantityHandler(
                $app->make(ParticipantRepository::class),
                $app->make(ParticipantEventDispatcher::class),
            );
        });
    }
}
