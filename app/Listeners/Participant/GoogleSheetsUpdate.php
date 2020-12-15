<?php

namespace App\Listeners\Participant;

use App\Services\Google\SheetsService;
use App\Events\Participant\ReferralQuantityChanged;

class GoogleSheetsUpdate
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
    public function handle(ReferralQuantityChanged $event)
    {
        $id = (string)$event->getDomainEvent()->getId();
        $this->sheets->updateParticipant($id);
    }
}
