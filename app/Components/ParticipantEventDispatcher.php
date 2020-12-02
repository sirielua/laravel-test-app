<?php

namespace App\Components;

use App\domain\dispatchers\EventDispatcher;

use App\domain\repositories\Participant\ParticipantRepository;
use App\domain\entities\Participant\events\ParticipantRegistrationConfirmed;
use App\domain\service\Participant\UpdateReferralQuantity\UpdateReferralQuantityCommand;
use App\domain\service\Participant\UpdateReferralQuantity\UpdateReferralQuantityHandler;

class ParticipantEventDispatcher implements EventDispatcher
{
    private $participants;

    public function __construct(ParticipantRepository $participants)
    {
        $this->participants = $participants;
    }

    public function dispatch(array $events = []): void
    {
        foreach ($events as $event) {
            if ($event instanceof ParticipantRegistrationConfirmed) {
                $participant = $this->participants->get($event->getId());

                if ($participant->getReferralId()) {
                    $command = new UpdateReferralQuantityCommand((string)$participant->getReferralId());
                    $handler = app()->make(UpdateReferralQuantityHandler::class);

                    $handler->handle($command);
                }
            }
        }
    }
}
