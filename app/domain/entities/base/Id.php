<?php

namespace App\domain\entities\base;

use Assert\Assertion;
use Ramsey\Uuid\Uuid;

class Id
{
    private $id;

    public function __construct(string $id)
    {
        Assertion::notEmpty($id);

        $this->id = $id;
    }

    public static function next(): self
    {
        return new static(Uuid::uuid4()->toString());
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function isEqualTo(self $other): bool
    {
        return $this->getId() === $other->getId();
    }
}
