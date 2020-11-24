<?php

namespace App\domain\service\Participant\ForceConfirmRegistration;

use App\domain\service\CommandTrait;
use Assert\Assertion;

class ForceConfirmRegistrationCommand
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
