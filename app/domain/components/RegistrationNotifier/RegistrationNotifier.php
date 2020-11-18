<?php

namespace App\domain\components\RegistrationNotifier;

use App\domain\entities\Participant\Participant;

interface RegistrationNotifier
{
    /**
     * @throws exceptions\FailedToNotifyException
     */
    public function notify(Participant $participant): void;
}
