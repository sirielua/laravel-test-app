<?php

namespace App\Components\Registration;

use App\domain\entities\Participant\Participant;

interface RegistrationConfirmationMessageGenerator
{
    public function generate(Participant $participant): string;
}
