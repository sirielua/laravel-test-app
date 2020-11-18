<?php

namespace App\domain\service\Contest\Activate;

use App\domain\service\Command;
use Assert\Assertion;

final class ActivateCommand extends Command
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
