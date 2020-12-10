<?php

namespace App\Services\Participant;

use App\domain\repositories\Participant\ParticipantRepository;
use App\domain\entities\Participant\Id;
use App\domain\service\Participant\UpdateReferralQuantity\UpdateReferralQuantityCommand;
use App\domain\service\Participant\UpdateReferralQuantity\UpdateReferralQuantityHandler;

class ParticipantService
{
    private $participants;

    public function __construct(ParticipantRepository $participants)
    {
        $this->participants = $participants;
    }

    function updateReferralQuantity($id)
    {
        $participant = $this->participants->get(new Id($id));

        if ($participant->getReferralId()) {
            $command = new UpdateReferralQuantityCommand((string)$participant->getReferralId());
            $handler = app()->make(UpdateReferralQuantityHandler::class);

            $handler->handle($command);
        }
    }
}
