<?php

namespace App\domain\service\Participant\ConfirmRegistration;

use App\domain\service\Command;
use Assert\Assertion;

class ConfirmRegistrationCommand extends Command
{
    private $id;
    private $code;

    public function __construct(string $id, string $code)
    {
        Assertion::notEmpty($id);
        Assertion::notEmpty($code);

        $this->id = $id;
        $this->code = $code;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}