<?php

namespace App\Events\Participant;

use App\Events\DomainEventForwarder;
use Illuminate\Foundation\Events\Dispatchable;
use App\domain\entities\Participant\events\ParticipantReferralQuantityChanged;

class ReferralQuantityChanged
{
    use DomainEventForwarder, Dispatchable;

    public function __construct(ParticipantReferralQuantityChanged $domainEvent)
    {
        $this->domainEvent = $domainEvent;
    }
}
