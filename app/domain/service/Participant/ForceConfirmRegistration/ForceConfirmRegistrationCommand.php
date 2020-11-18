<?php

namespace App\domain\service\Participant\ForceConfirmRegistration;

use App\domain\service\Command;
use Assert\Assertion;

class ForceConfirmRegistrationCommand extends Command
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
