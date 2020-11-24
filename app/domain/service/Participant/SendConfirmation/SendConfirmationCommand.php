<?php

namespace App\domain\service\Participant\SendConfirmation;

use App\domain\service\CommandTrait;
use Assert\Assertion;

class SendConfirmationCommand
{
    use CommandTrait;

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
