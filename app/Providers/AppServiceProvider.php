<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\Participant\RegistrationService;
use App\Services\Participant\RegistrationData\StoreContestInCookies;
use App\Services\Participant\RegistrationData\StoreReferralInCookiesIfNew;
use App\Services\Participant\RegistrationData\SessionRegistrationData;
use App\Services\Participant\RegistrationData\SimpleRegistrationData;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(RegistrationService::class, function ($app) {
            return new RegistrationService(
                new StoreContestInCookies(
                    new StoreReferralInCookiesIfNew(
                        new SessionRegistrationData(
                            new SimpleRegistrationData(),
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
