<?php

namespace App\Services\Participant\RegistrationData;

use App\Services\Participant\RegistrationData;

class SimpleRegistrationData implements RegistrationData
{
    private $stage;

    private $id;
    private $contestId;
    private $firstName;
    private $lastName;
    private $phone;
    private $referralId;

    public function setStage($value)
    {
        $validStages = [
            RegistrationData::STAGE_REGISTER,
            RegistrationData::STAGE_VERIFICATION,
            RegistrationData::STAGE_CONFIRMED,
            RegistrationData::STAGE_SHARE,
            RegistrationData::STAGE_MESSANGER,
        ];

        if (!in_array($value, $validStages)) {
            throw new \LogicException('Invalid stage value "' . $value . '"');
        }

        $this->stage = $value;
    }

    public function getStage()
    {
        return $this->stage;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setContestId($value)
    {
        $this->contestId = $value;
    }

    public function getContestId()
    {
        return $this->contestId;
    }

    public function setFirstName($value)
    {
        $this->firstName = $value;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setLastName($value)
    {
        $this->lastName = $value;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setPhone($value)
    {
        $this->phone = $value;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setReferralId($value)
    {
        $this->referralId = $value;
    }

    public function getReferralId()
    {
        return $this->referralId;
    }
}
