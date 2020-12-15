<?php

namespace App\Components\Sms\Balance;

interface BalanceChecker
{
    public function getBalance(): Balance;
}

