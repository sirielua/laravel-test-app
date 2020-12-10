<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\Participant\RegistrationService;
use App\Services\Participant\RegistrationData\StoreContestInCookies;
use App\Services\Participant\RegistrationData\StoreReferralInCookiesIfNew;
use App\Services\Participant\RegistrationData\SessionRegistrationData;
use App\Services\Participant\RegistrationData\MemoryRegistrationData;

use App\Components\ParticipantEventDispatcher;

use App\Services\Participant\ParticipantService;
use App\domain\repositories\Participant\ParticipantRepository;
use App\Services\Google\SheetsService;
use App\Services\Facebook\MessengerService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ParticipantEventDispatcher::class, function ($app) {
            return new ParticipantEventDispatcher(
                $app->make(ParticipantService::class),
                $app->make(SheetsService::class),
//                $app->make(MessengerService::class),
            );
        });

        $this->app->singleton(ParticipantService::class, function ($app) {
            return new ParticipantService(
                $app->make(ParticipantRepository::class)
            );
        });

        $this->app->singleton(RegistrationService::class, function ($app) {
            return new RegistrationService(
                new StoreContestInCookies(
                    new StoreReferralInCookiesIfNew(
                        new SessionRegistrationData(
                            new MemoryRegistrationData(),
                            $app->make('Illuminate\\Http\\Request')->session()
                        )
                    )
                )
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
