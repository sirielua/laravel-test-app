<?php

namespace App\Listeners\Participant;

use App\Services\Google\SheetsService;
use App\Events\Participant\RegistrationConfirmed;

class GoogleSheetsExport
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
    public function handle(RegistrationConfirmed $event)
    {
        $id = (string)$event->getDomainEvent()->getId();
        $this->sheets->updateParticipant($id);
    }
}
