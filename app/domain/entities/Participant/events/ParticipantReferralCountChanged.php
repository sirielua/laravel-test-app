<?php

namespace App\domain\entities\Participant\events;

use App\domain\entities\Participant\Id;

class ParticipantReferralCountChanged
{
    private $id;

    public function __construct(Id $id)
    {
        $this->id = $id;
    }

    public function getId(): Id
    {
        return $this->id;
    }
}
