<?php

namespace App\Listeners\Participant;

use App\Services\Google\SheetsService;
use App\Events\Participant\Removed;

class GoogleSheetsRemove
{
    private $sheets;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(SheetsService $sheets)
    {
        $this->sheets = $sheets;
    }

    /**
     * Handle the event.
     *
     * @param RegistrationConfirmed  $event
     * @return void
     */
    public function handle(Removed $event)
    {
        $id = (string)$event->getDomainEvent()->getId();
        $this->sheets->removeParticipant($id);
    }
}
