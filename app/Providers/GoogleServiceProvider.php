<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

use Google\Client;
use Google_Service_Sheets;
use App\Services\Google\SheetsService;
use App\Components\Google\SheetsApi;

use App\Events\Participant\RegistrationConfirmed;
use App\Listeners\Participant\GoogleSheetsExport;

use App\Events\Participant\ReferralQuantityChanged;
use App\Listeners\Participant\GoogleSheetsUpdate;

use App\Events\Participant\Removed;
use App\Listeners\Participant\GoogleSheetsRemove;

class GoogleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $credentials = base_path(env('GOOGLE_API_CREDENTIALS', 'google-credentials.json'));
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $credentials);

        $this->app->singleton(Client::class, function ($app) {
            $client = new Client();
            $client->useApplicationDefaultCredentials();
            $client->setScopes(\Google_Service_Sheets::SPREADSHEETS);
            return $client;
        });

        $this->app->singleton(SheetsService::class, function ($app) {
            $client = $app->make(Client::class);
            $service = new Google_Service_Sheets($client);
            $spreadsheetId = env('GOOGLE_API_SPREADSHEET_ID');
            $api = new SheetsApi($service);

            return new SheetsService($spreadsheetId, $api);
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
            RegistrationConfirmed::class, [GoogleSheetsExport::class, 'handle']
        );

        Event::listen(
            ReferralQuantityChanged::class, [GoogleSheetsUpdate::class, 'handle']
        );

        Event::listen(
            Removed::class, [GoogleSheetsRemove::class, 'handle']
        );
    }
}
