<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Providers\GoogleServiceProvider;
use App\Providers\SmsServiceProvider;
use App\Providers\DomainServiceProvider;

use App\Services\Participant\RegistrationService;
use App\Services\Participant\RegistrationData\StoreContestInCookies;
use App\Services\Participant\RegistrationData\StoreReferralInCookiesIfNew;
use App\Services\Participant\RegistrationData\SessionRegistrationData;
use App\Services\Participant\RegistrationData\MemoryRegistrationData;

use App\Events\Participant\RegistrationConfirmed;
use App\Listeners\Participant\ReferralQuantityUpdate;

class AppServiceProvider extends ServiceProvider
{
    private $providers = [
        DomainServiceProvider::class,
        GoogleServiceProvider::class,
        SmsServiceProvider::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerProviders();
        $this->registerServices();
    }

    private function registerProviders()
    {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }

    private function registerServices()
    {
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
        Event::listen(
            RegistrationConfirmed::class, [ReferralQuantityUpdate::class, 'handle']
        );
    }
}
