<?php

namespace App\domain\service\Participant\LinkFacebook;

use App\domain\service\Command;
use Assert\Assertion;

class LinkFacebookCommand extends Command
{
    private $id;

    public function __construct(string $id)
    {
        Assertion::notEmpty($id);
        
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
