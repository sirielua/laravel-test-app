<?php

namespace App\Components\Registration;

use App\domain\entities\Participant\Participant;

class RegistrationLinkConfirmationMessageGenerator implements RegistrationConfirmationMessageGenerator
{
    public function generate(Participant $participant): string
    {
        $id = (string)$participant->getId();
        $code = $participant->getRegistrationData()->getConfirmationCode();

        $url = route('participants.confirm-link', [
            'id' => $id,
            'code' => $code,
        ]);

        return 'Your confirmation link is: <a href="' . $url . '">' . $url . '</a>';
    }
}
