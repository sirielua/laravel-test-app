<?php

namespace App\domain\service\Participant\Register;

use App\domain\service\CommandTrait;
use Assert\Assertion;

class RegisterCommand
{
    use CommandTrait;

    private $firstName;
    private $lastName;
    private $phone;

    public function __construct($firstName, $lastName, $phone)
    {
        Assertion::notEmpty($firstName);
        Assertion::notEmpty($lastName);
        Assertion::notEmpty($phone);

        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phone = $phone;
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
}
