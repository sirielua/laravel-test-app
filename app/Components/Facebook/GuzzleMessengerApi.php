<?php

namespace App\Components\Facebook;

use GuzzleHttp\Client;

class GuzzleMessengerApi implements MessengerApi
{
    private $token;
    private $baseUri = 'https://graph.facebook.com/v9.0/me/';

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function sendMesage($psid, $message)
    {
        $uri = 'messages?access_token='.$this->token;
        $req = [
            'recipient' => ['id' => $psid],
            'message' => ['text' => $message],
        ];

        $this->getClient()->post($uri, ['json' => $req]);
    }

    private function getClient()
    {
        return new Client([
            'base_uri' => $this->baseUri,
        ]);
    }
}
