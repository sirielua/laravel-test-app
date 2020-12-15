<?php

namespace App\Components\Sms\Balance;

class Balance
{
    private $smsCount;

    public function __construct($smsCount)
    {
        $this->smsCount = $smsCount;
    }

    public function getSmsCount()
    {
        return $this->smsCount;
    }
}
