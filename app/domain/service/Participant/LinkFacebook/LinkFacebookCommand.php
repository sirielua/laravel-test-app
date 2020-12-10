<?php

namespace App\domain\service\Participant\LinkFacebook;

use App\domain\service\CommandTrait;
use Assert\Assertion;

class LinkFacebookCommand
{
    use CommandTrait;

    private $id;
    private $facebookId;

    public function __construct(string $id, string $facebookId)
    {
        Assertion::notEmpty($id);
        Assertion::notEmpty($facebookId);

        $this->id = $id;
        $this->facebookId = $facebookId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFacebookId(): string
    {
        return $this->facebookId;
    }
}
