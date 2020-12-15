<?php

namespace App\Events\Participant;

use App\Events\DomainEventForwarder;
use Illuminate\Foundation\Events\Dispatchable;
use App\domain\entities\Participant\events\ParticipantFacebookIdAttached;

class FacebookIdAttached
{
    use DomainEventForwarder, Dispatchable;

    public function __construct(ParticipantFacebookIdAttached $domainEvent)
    {
        $this->domainEvent = $domainEvent;
    }
}
