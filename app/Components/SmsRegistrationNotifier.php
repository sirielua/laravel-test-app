<?php

namespace App\Components;

use App\domain\components\RegistrationNotifier\RegistrationNotifier;
use App\domain\components\RegistrationNotifier\exceptions\FailedToNotifyException;
use App\domain\entities\Participant\Participant;

use App\Components\Sms\SmsApi;

class SmsRegistrationNotifier implements RegistrationNotifier
{
    private $api;

    public function __construct(SmsApi $api)
    {
        $this->api = $api;
    }

    /**
     * @throws exceptions\FailedToNotifyException
     */
    public function notify(Participant $participant): void
    {
        $code = $participant->getRegistrationData()->getConfirmationCode();

        try {
            $this->api->send((string)$participant->getPhone(), 'Your confirmation code is: '. $code);
        } catch (\Exception $e) {
            throw new FailedToNotifyException($e->getMessage(), $e->getCode());
        }
    }
}
