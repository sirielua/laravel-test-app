<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Components\Sms\Providers\TanzaniaMessaging\ClientFactory;
use App\Components\Sms\SmsApi;
use App\Components\Sms\SmsApiFactory;
use App\Components\Sms\Providers\TanzaniaMessaging\TanzaniaMessagingFactory;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SmsApiFactory::class, function ($app) {
            $username = env('NEXTSMS_USERNAME');
            $password = env('NEXTSMS_PASSWORD');
            $senderId = env('NEXTSMS_SENDER_ID');
            $baseUri =  env('NEXTSMS_URI');

            $client = (new ClientFactory($username, $password, $baseUri))->getClient();

            return new TanzaniaMessagingFactory($client, $senderId);
        });

        $this->app->singleton(SmsApi::class, function ($app) {
            return new SmsApi($app->make(SmsApiFactory::class));
        });
    }
}
