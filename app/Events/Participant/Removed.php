<?php

namespace App\Events\Participant;

use App\Events\DomainEventForwarder;
use Illuminate\Foundation\Events\Dispatchable;
use App\domain\entities\Participant\events\ParticipantRemoved;

class Removed
{
    use DomainEventForwarder, Dispatchable;

    public function __construct(ParticipantRemoved $domainEvent)
    {
        $this->domainEvent = $domainEvent;
    }
}
