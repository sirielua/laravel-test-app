<?php

namespace App\Services\Participant\RegistrationData;

use App\Services\Participant\RegistrationData;
use Illuminate\Support\Facades\Cookie;

class StoreContestInCookies extends Decorator implements RegistrationData
{
    private $lifetimeInMinutes;
    private $prefix;

    private $contestId;

    public function __construct(RegistrationData $next, $lifetimeInMinutes = 12*60, $prefix = 'participant_registration_data')
    {
        $this->lifetimeInMinutes = $lifetimeInMinutes;
        $this->prefix = $prefix;

        parent::__construct($next);
    }

    public function setContestId($value)
    {
        $this->contestId = $value;
        Cookie::queue($this->prefix.'.contest_id', $value, $this->lifetimeInMinutes);

        return $this->next->setContestId($value);
    }

    public function getContestId()
    {
        return $this->contestId ?? Cookie::get($this->prefix.'.contest_id') ?? $this->next->getContestId();
    }
}

