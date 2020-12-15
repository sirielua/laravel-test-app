<?php

namespace App\Components\Sms;

class SmsApi
{
    private $factory;

    public function __construct(SmsApiFactory $factory)
    {
        $this->factory = $factory;
    }

    public function send($to, $message)
    {
        $sender = $this->factory->getSender();
        return $sender->send($to, $message);
    }

    public function getBalance()
    {
        $balanceChecker = $this->factory->getBalanceChecker();
        return $balanceChecker->getBalance();
    }
}
