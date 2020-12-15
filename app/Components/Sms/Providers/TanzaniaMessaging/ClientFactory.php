<?php

namespace App\Components\Sms\Providers\TanzaniaMessaging;

use GuzzleHttp\Client;

class ClientFactory
{
    private $username;
    private $password;
    private $baseUri;

    private $client;

    public function __construct($username, $password, $baseUri = 'https://messaging-service.co.tz/api/sms/v1/')
    {
        $this->username = $username;
        $this->password = $password;
        $this->baseUri = $baseUri;
    }

    public function getClient()
    {
        if ($this->client === null) {
            $this->client = new Client([
                'base_uri' => $this->baseUri,
                'headers' => [
                    'Authorization' => 'Basic ' . $this->getAuthToken(),
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);
        }

        return $this->client;
    }

    private function getAuthToken()
    {
        $raw = $this->username . ':' . $this->password;
        return \base64_encode($raw);
    }
}
