<?php

namespace App\domain\entities\Participant;

use Assert\Assertion;

class RegistrationStatus
{
    const UNCONFIRMED = 1;
    const CONFIRMED = 2;

    private $status;

    public function __construct(int $status)
    {
        Assertion::inArray($status, array_keys(self::getLabels()));

        $this->status = $status;
    }

    public function __toString(): string
    {
        $labels = self::getLabels();
        return $labels[$this->status];
    }

    private static function getLabels(): array
    {
        return [
            self::UNCONFIRMED => 'unconfirmed',
            self::CONFIRMED => 'confirmed',
        ];
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function isEqualTo(self $status): bool
    {
        return $this->getStatus() === $status->getStatus();
    }
}
