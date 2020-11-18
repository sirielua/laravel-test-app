<?php

namespace App\domain\entities\Participant;

use Assert\Assertion;

class Phone
{
    private $phone;

    public function __construct(string $phone)
    {
        Assertion::notEmpty($phone);

        $this->phone = $phone;
    }

    public function __toString(): string
    {
        return $this->phone;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function isEqualTo(self $phone): string
    {
        return (string)$this === (string)$phone;
    }
}
