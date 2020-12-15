<?php

namespace App\Components\Sms\Providers\TanzaniaMessaging;

use App\Components\Sms\SmsApiFactory;
use GuzzleHttp\Client;

use App\Components\Sms\Sender\Sender;
use App\Components\Sms\Balance\BalanceChecker;

class TanzaniaMessagingFactory extends SmsApiFactory
{
    private $client;

    public function __construct(Client $client, $senderId)
    {
        $this->client = $client;
        $this->senderId = $senderId;
    }

    public function getSender(): Sender
    {
        return new TanzaniaSender($this->client, $this->senderId);
    }

    public function getBalanceChecker(): BalanceChecker
    {
        return new TanzaniaBalanceChecker($this->client);
    }
}
