<?php

namespace App\Services\Participant\RegistrationData;

use App\Services\Participant\RegistrationData;
use Illuminate\Support\Facades\Cookie;

class StoreReferralInCookiesIfNew extends Decorator implements RegistrationData
{
    private $lifetimeInMinutes;
    private $prefix;

    private $referralId;

    public function __construct(RegistrationData $next, $lifetimeInMinutes = 12*60, $prefix = 'participant_registration_data')
    {
        $this->lifetimeInMinutes = $lifetimeInMinutes;
        $this->prefix = $prefix;

        parent::__construct($next);
    }

    public function setReferralId($value)
    {
        $this->next->setReferralId($value);

        if ($this->next->getStage() === RegistrationData::STAGE_REGISTER) {
            $this->referralId = $value;
            Cookie::queue($this->prefix.'.referral_id', $value, $this->lifetimeInMinutes);
        }
    }

    public function getReferralId()
    {
        if ($this->next->getStage() === RegistrationData::STAGE_REGISTER) {
            return $this->referralId ?? Cookie::get($this->prefix.'.referral_id') ?? $this->next->getReferralId();
        } else {
            return $this->next->getReferralId();
        }
    }
}

