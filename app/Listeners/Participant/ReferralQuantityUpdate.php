<?php

namespace App\Listeners\Participant;

use App\Services\Participant\ParticipantService;
use App\Events\Participant\RegistrationConfirmed;

class ReferralQuantityUpdate
{
    private $participants;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ParticipantService $participants)
    {
        $this->participants = $participants;
    }

    /**
     * Handle the event.
     *
     * @param RegistrationConfirmed  $event
     * @return void
     */
    public function handle(RegistrationConfirmed $event)
    {
        $id = (string)$event->getDomainEvent()->getId();
        $this->participants->updateReferralQuantity($id);
    }
}
