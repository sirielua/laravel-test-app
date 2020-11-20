<?php

namespace App\domain\service\Contest\Activate;

use App\domain\service\CommandTrait;
use Assert\Assertion;

class ActivateCommand extends Command
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
