<?php

namespace App\Components\Sms;

use App\Components\Sms\Result\Result;
use App\Components\Sms\Balance\Balance;

class SmsApi
{
    private $factory;

    public function __construct(SmsApiFactory $factory)
    {
        $this->factory = $factory;
    }

    public function send($to, $message): Result
    {
        $sender = $this->factory->getSender();
        return $sender->send($to, $message);
    }

    public function getBalance(): Balance
    {
        $balanceChecker = $this->factory->getBalanceChecker();
        return $balanceChecker->getBalance();
    }
}
