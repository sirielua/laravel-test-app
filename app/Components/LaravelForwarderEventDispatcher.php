<?php

namespace App\Components;

use App\domain\dispatchers\EventDispatcher;
use App\domain\entities\Participant\events\ParticipantRegistrationConfirmed;
use App\domain\entities\Participant\events\ParticipantReferralQuantityChanged;
use App\domain\entities\Participant\events\ParticipantFacebookIdAttached;
use App\domain\entities\Participant\events\ParticipantRemoved;

use App\Events\Participant\RegistrationConfirmed;
use App\Events\Participant\ReferralQuantityChanged;
use App\Events\Participant\FacebookIdAttached;
use App\Events\Participant\Removed;

class LaravelForwarderEventDispatcher implements EventDispatcher
{
    private $config = [
        ParticipantRegistrationConfirmed::class => RegistrationConfirmed::class,
        ParticipantReferralQuantityChanged::class => ReferralQuantityChanged::class,
        ParticipantFacebookIdAttached::class => FacebookIdAttached::class,
        ParticipantRemoved::class => Removed::class,
    ];

    public function dispatch(array $events = []): void
    {
        foreach ($events as $domainEvent) {
            $this->forwardEvent($domainEvent);
        }
    }

    private function forwardEvent($domainEvent)
    {
        $eventClass = $this->config[get_class($domainEvent)] ?? null;
        if ($eventClass) {
            $eventClass::dispatch($domainEvent);
        }
    }
}
