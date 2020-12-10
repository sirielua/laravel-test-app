<?php

namespace App\Components;

use App\domain\dispatchers\EventDispatcher;
use App\domain\entities\Participant\events\ParticipantRegistrationConfirmed;
use App\domain\entities\Participant\events\ParticipantReferralQuantityChanged;
use App\domain\entities\Participant\events\ParticipantFacebookIdAttached;
use App\Services\Facebook\MessengerService;
use App\Services\Participant\ParticipantService;
use App\Services\Google\SheetsService;

class ParticipantEventDispatcher implements EventDispatcher
{
    private $participants;
    private $sheets;
    private $messenger;

    private $handlers = [
        ParticipantRegistrationConfirmed::class => [
            'updateReferralQuantity',
//            'notifyAboutNewReferralViaMessenger',
            'exportParticipantToSheets',
        ],
        ParticipantReferralQuantityChanged::class => [
            'updateParticipantViaSheets',
        ],
        ParticipantFacebookIdAttached::class => [
//            'welcomeViaMessenger'
        ],
    ];

    public function __construct(ParticipantService $participants, SheetsService $sheets, MessengerService $messenger = null)
    {
        $this->participants = $participants;
        $this->sheets = $sheets;
        $this->messenger = $messenger;
    }

    public function dispatch(array $events = []): void
    {
        foreach ($events as $event) {
            $handlers = $this->handlers[get_class($event)] ?? [];
            foreach ($handlers as $method) {
                $this->$method($event);
            }
        }
    }

    private function updateReferralQuantity(ParticipantRegistrationConfirmed $event)
    {
        $this->participants->updateReferralQuantity((string)$event->getId());
    }

    private function notifyAboutNewReferralViaMessenger(ParticipantRegistrationConfirmed $event)
    {
        $this->messenger->notifyAboutNewReferral((string)$event->getId());
    }

    private function exportParticipantToSheets(ParticipantRegistrationConfirmed $event)
    {
        $this->sheets->updateParticipant((string)$event->getId());
    }

    private function updateParticipantViaSheets(ParticipantReferralQuantityChanged $event)
    {
        $this->sheets->updateParticipant((string)$event->getId());
    }

    private function welcomeViaMessenger(ParticipantFacebookIdAttached $event)
    {
        $this->messenger->sendWelcomeMessage((string)$event->getId());
    }
}
