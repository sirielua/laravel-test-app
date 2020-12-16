<?php

namespace App\Components\Registration;

use App\domain\components\RegistrationNotifier\RegistrationNotifier;
use App\domain\components\RegistrationNotifier\exceptions\FailedToNotifyException;
use App\domain\entities\Participant\Participant;

use App\Components\Sms\SmsApi;

class SmsRegistrationNotifier implements RegistrationNotifier
{
    private $messageGen;
    private $api;

    public function __construct(RegistrationConfirmationMessageGenerator $messageGen, SmsApi $api)
    {
        $this->messageGen = $messageGen;
        $this->api = $api;
    }

    /**
     * @throws exceptions\FailedToNotifyException
     */
    public function notify(Participant $participant): void
    {
        try {
            $this->api->send((string)$participant->getPhone(), $this->messageGen->generate($participant));
        } catch (\Exception $e) {
            throw new FailedToNotifyException($e->getMessage(), $e->getCode());
        }
    }
}
