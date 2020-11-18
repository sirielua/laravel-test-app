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
        if ($participant->getIsRegistrationConfirmed()) {
            throw new exceptions\FailedToNotifyException('Registration already confirmed.');
        }

        // do nothing
    }
}
