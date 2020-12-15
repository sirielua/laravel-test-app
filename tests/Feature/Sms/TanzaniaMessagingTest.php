<?php

namespace Tests\Feature\Sms;

use Tests\TestCase;

use App\Components\Sms\Providers\TanzaniaMessaging\ClientFactory;
use App\Components\Sms\SmsApi;
use App\Components\Sms\Providers\TanzaniaMessaging\TanzaniaMessagingFactory;

use App\Components\Sms\Balance\Balance;
use App\Components\Sms\Sender\Result;

class TanzaniaMessagingTest extends TestCase
{
    use SmsApiTest;

    private static $realApi;

    public function setUp(): void
    {
        parent::setUp();

        if (self::$api === null) {
            $username = env('NEXTSMS_USERNAME');
            $password = env('NEXTSMS_PASSWORD');
            $senderId = env('NEXTSMS_SENDER_ID');

            $testBaseUri = 'https://messaging-service.co.tz/api/sms/v1/test/';
            $baseUri = 'https://messaging-service.co.tz/api/sms/v1/';

            $testClient = (new ClientFactory($username, $password, $testBaseUri))->getClient();
            self::$api = new SmsApi(new TanzaniaMessagingFactory($testClient, $senderId));

            $realClient = (new ClientFactory($username, $password, $baseUri))->getClient();
            self::$realApi = new SmsApi(new TanzaniaMessagingFactory($realClient, $senderId));
        }
    }

    public function testCheckBalance()
    {
       $balance = self::$realApi->getBalance();

       $this->assertInstanceOf(Balance::class, $balance);
       $this->assertTrue(\is_integer($balance->getSmsCount()));
       $this->assertGreaterThanOrEqual(0, $balance->getSmsCount());
    }

    public function testSendSmsId()
    {
        $result = self::$api->send(1234567890, 'Hello World!');

        $this->assertInstanceOf(Result::class, $result);
        $this->assertGreaterThan(0, $result->getId());
    }
}
