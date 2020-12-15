<?php

namespace App\Listeners\Participant;

use App\Services\Facebook\MessengerService;
use App\Events\Participant\FacebookIdAttached;

class MessengerWelcomeNotification
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
    public function handle(FacebookIdAttached $event)
    {
        $id = (string)$event->getDomainEvent()->getId();
        $this->messenger->sendWelcomeMessage($id);
    }
}
