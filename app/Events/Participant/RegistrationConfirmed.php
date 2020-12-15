<?php

namespace App\Events\Participant;

use App\Events\DomainEventForwarder;
use Illuminate\Foundation\Events\Dispatchable;
use App\domain\entities\Participant\events\ParticipantRegistrationConfirmed;

class RegistrationConfirmed
{
    use DomainEventForwarder, Dispatchable;

    public function __construct(ParticipantRegistrationConfirmed $domainEvent)
    {
        $this->domainEvent = $domainEvent;
    }
}
