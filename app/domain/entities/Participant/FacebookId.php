<?php

namespace App\domain\entities\Participant;

use Assert\Assertion;

class FacebookId
{
    private $facebookId;

    public function __construct(string $facebookId)
    {
        Assertion::notEmpty($facebookId);

        $this->facebookId = $facebookId;
    }

    public function __toString(): string
    {
        return $this->facebookId;
    }

    public function getFacebookId(): string
    {
        return $this->facebookId;
    }
}
