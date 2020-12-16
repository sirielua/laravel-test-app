<?php

namespace App\Components\Registration;

use App\domain\entities\Participant\Participant;

class RegistrationCodeConfirmationMessageGenerator implements RegistrationConfirmationMessageGenerator
{
    public function generate(Participant $participant): string
    {
        $code = $participant->getRegistrationData()->getConfirmationCode();

        return 'Your confirmation code is: '. $code;
    }
}
