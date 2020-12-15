<?php

namespace App\Listeners\Participant;

use App\Services\Facebook\MessengerService;
use App\Events\Participant\RegistrationConfirmed;

class MessengerNewReferralNotification
{
    private $messenger;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(MessengerService $messenger)
    {
        $this->messenger = $messenger;
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
        $this->messenger->notifyAboutNewReferral($id);
    }
}
