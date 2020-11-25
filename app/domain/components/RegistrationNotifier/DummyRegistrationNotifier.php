<?php

namespace App\domain\components\RegistrationNotifier;

use App\domain\entities\Participant\Participant;

class DummyRegistrationNotifier implements RegistrationNotifier
{
    /**
     * @throws exceptions\FailedToNotifyException
     */
    public function notify(Participant $participant): void
    {
        // do nothing
    }
}
