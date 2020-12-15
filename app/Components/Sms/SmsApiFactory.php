<?php

namespace App\Components\Sms;

use App\Components\Sms\Sender\Sender;
use App\Components\Sms\Balance\BalanceChecker;

abstract class SmsApiFactory
{
    abstract public function getSender(): Sender;
    abstract public function getBalanceChecker(): BalanceChecker;
}
