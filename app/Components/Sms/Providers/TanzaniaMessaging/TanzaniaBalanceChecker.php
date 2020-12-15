<?php

namespace App\Components\Sms\Providers\TanzaniaMessaging;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use App\Components\Sms\Balance\BalanceChecker;
use App\Components\Sms\Balance\Balance;
use App\Components\Sms\Exceptions\NoResponseException;

class TanzaniaBalanceChecker implements BalanceChecker
{
    use ResponceFilterTrait;

    private $client;
    private $uri = 'balance';

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getBalance(): Balance
    {
        try {
            $response = $this->executeApiCall();
            $data = json_decode((string)$response->getBody(), true);
        } catch (ClientException $e) {
            throw $this->formatException($e);
        }

        return $this->formatResponseData($data);
    }

    private function executeApiCall()
    {
        return $this->client->get($this->uri);
    }

    private function formatResponseData($data)
    {
        $balance = $data['sms_balance'] ?? false;

        if ($balance !== false) {
            return new Balance($balance);
        } else {
            throw new NoResponseException();
        }
    }
}
