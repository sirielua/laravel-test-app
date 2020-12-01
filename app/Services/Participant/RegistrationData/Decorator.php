<?php

namespace App\Services\Participant\RegistrationData;

use App\Services\Participant\RegistrationData;

abstract class Decorator implements RegistrationData
{
    protected $next;

    public function __construct(RegistrationData $next)
    {
        $this->next = $next;
    }

    public function setStage($value)
    {
        $this->next->setStage($value);
    }

    public function getStage()
    {
        return $this->next->getStage();
    }

    public function setId($value)
    {
        $this->next->setId($value);
    }

    public function getId()
    {
        return $this->next->getId();
    }

    public function setContestId($value)
    {
        $this->next->setContestId($value);
    }

    public function getContestId()
    {
        return $this->next->getContestId();
    }

    public function setFirstName($value)
    {
        $this->next->setFirstName($value);
    }

    public function getFirstName()
    {
        return $this->next->getFirstName();
    }

    public function setLastName($value)
    {
        $this->next->setLastName($value);
    }

    public function getLastName()
    {
        return $this->next->getLastName();
    }

    public function setPhone($value)
    {
        $this->next->setPhone($value);
    }

    public function getPhone()
    {
        return $this->next->getPhone();
    }

    public function setReferralId($value)
    {
        $this->next->setReferralId($value);
    }

    public function getReferralId()
    {
        return $this->next->getReferralId();
    }
}
