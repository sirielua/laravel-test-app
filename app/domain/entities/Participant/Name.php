<?php

namespace App\domain\entities\Participant;

use Assert\Assertion;

class Name
{
    private $firstName;
    private $lastName;

    public function __construct(string $firstName, string $lastName)
    {
        Assertion::notEmpty($firstName);
        Assertion::notEmpty($lastName);

        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function __toString(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }
}
