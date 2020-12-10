<?php

namespace App\Services\Facebook;

use App\Components\Facebook\MessengerApi;
use App\domain\repositories\Participant\ParticipantRepository;
use App\domain\entities\Participant\Id;

class MessengerService
{
    private $api;
    private $participants;

    public function __construct(MessengerApi $api, ParticipantRepository $participants)
    {
        $this->api = $api;
        $this->participants = $participants;
    }
    public function sendWelcomeMessage($id)
    {
        $participant = $this->participant->get(new Id($id));
        $psid = $participant->getFacebookId();

        $this->api->sendMesage($psid, view('facebook._welcome')->render());
    }

    public function notifyAboutNewReferral($id)
    {
        $referral = $this->participant->get(new Id($id));

        if ($referral_id = $referral->getReferralId()) {
            $participant = $this->participant->get(new Id($referral_id));

            if ($psid = $participant->getFacebookId()) {
                $this->api->sendMesage($psid, view('facebook._new_referral', [
                    'participant' => $participant,
                    'referral' => $referral,
                ])->render());
            }
        }
    }
}
