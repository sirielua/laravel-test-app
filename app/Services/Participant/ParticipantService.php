<?php

namespace App\Services\Participant;

use App\domain\service\Participant\UpdateReferralQuantity\UpdateReferralQuantityCommand;
use App\domain\service\Participant\UpdateReferralQuantity\UpdateReferralQuantityHandler;

use App\Models\Participant;

class ParticipantService
{
    function updateReferralQuantity($id)
    {
        $participant = Participant::findOrFail($id);
        $referral_id = $participant->referral_id;

        if ($referral_id) {
            $command = new UpdateReferralQuantityCommand($referral_id);
            $handler = app()->make(UpdateReferralQuantityHandler::class);

            $handler->handle($command);
        }
    }
}
