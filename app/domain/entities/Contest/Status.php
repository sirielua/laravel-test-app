<?php

namespace App\domain\entities\Contest;

use Assert\Assertion;

class Status
{
    const ACTIVE = 1;
    const INACTIVE = 0;

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
            self::ACTIVE => 'active',
            self::INACTIVE => 'inactive',
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
