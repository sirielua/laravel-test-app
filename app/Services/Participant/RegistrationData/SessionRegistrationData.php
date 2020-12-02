<?php

namespace App\Services\Participant\RegistrationData;

use App\Services\Participant\RegistrationData;
use Illuminate\Contracts\Session\Session;

class SessionRegistrationData extends Decorator implements RegistrationData
{
    private $session;
    private $prefix;

    public function __construct(RegistrationData $next, Session $session, $prefix = 'participant_registration_data')
    {
        $this->session = $session;
        $this->prefix = $prefix;

        parent::__construct($next);
    }

    public function setStage($value)
    {
        $this->next->setStage($value);

        $this->session->put($this->prefix.'.stage', $value);
    }

    public function getStage()
    {
        return $this->session->get($this->prefix.'.stage', $this->next->getStage());
    }

    public function setId($value)
    {
        $this->next->setId($value);

        $this->session->put($this->prefix.'.id', $value);
    }

    public function getId()
    {
        return $this->session->get($this->prefix.'.id', $this->next->getId());
    }

    public function setContestId($value)
    {
        $this->next->setContestId($value);

        $this->session->put($this->prefix.'.contest_id', $value);
    }

    public function getContestId()
    {
        return $this->session->get($this->prefix.'.contest_id', $this->next->getContestId());
    }

    public function setFirstName($value)
    {
        $this->next->setFirstName($value);

        $this->session->put($this->prefix.'.first_name', $value);
    }

    public function getFirstName()
    {
        return $this->session->get($this->prefix.'.first_name', $this->next->getFirstName());
    }

    public function setLastName($value)
    {
        $this->next->setLastName($value);

        $this->session->put($this->prefix.'.last_name', $value);
    }

    public function getLastName()
    {
        return $this->session->get($this->prefix.'.last_name', $this->next->getLastName());
    }

    public function setPhone($value)
    {
        $this->next->setPhone($value);

        $this->session->put($this->prefix.'.phone', $value);
    }

    public function getPhone()
    {
        return $this->session->get($this->prefix.'.phone', $this->next->getPhone());
    }

    public function setReferralId($value)
    {
        $this->next->setReferralId($value);

        $this->session->put($this->prefix.'.referral_id', $value);
    }

    public function getReferralId()
    {
        return $this->session->get($this->prefix.'.referral_id', $this->next->getReferralId());
    }
}
