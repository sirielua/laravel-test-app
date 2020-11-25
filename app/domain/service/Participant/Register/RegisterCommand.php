<?php

namespace App\domain\service\Participant\Register;

use App\domain\service\CommandTrait;
use Assert\Assertion;

class RegisterCommand
{
    use CommandTrait;

    private $contestId;
    private $firstName;
    private $lastName;
    private $phone;
    private $referralId;

    public function __construct($contestId, $firstName, $lastName, $phone, $referralId = null)
    {
        Assertion::notEmpty($contestId);
        Assertion::notEmpty($firstName);
        Assertion::notEmpty($lastName);
        Assertion::notEmpty($phone);

        $this->contestId = $contestId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phone = $phone;
        $this->referralId = $referralId;
    }

    public function getContestId()
    {
        return $this->contestId;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getReferralId()
    {
        return $this->referralId;
    }
}
