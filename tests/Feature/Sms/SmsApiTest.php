<?php

namespace Tests\Feature\Sms;

use App\Components\Sms\Sender\Result;
use App\Components\Sms\Balance\Balance;

trait SmsApiTest
{
    protected static $api;

    public function testSendSms()
    {
        $result = self::$api->send(1234567890, 'Hello World!');

        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->isSuccess(), $result->getMessage());
    }

    public function testCheckBalance()
    {
       $balance = self::$api->getBalance();

       $this->assertInstanceOf(Balance::class, $balance);
       $this->assertTrue(\is_integer($balance->getSmsCount()));
       $this->assertGreaterThanOrEqual(0, $balance->getSmsCount());
    }
}

