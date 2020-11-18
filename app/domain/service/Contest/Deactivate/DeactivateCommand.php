<?php

namespace App\domain\service\Contest\Deactivate;

use App\domain\service\Command;
use Assert\Assertion;

final class DeactivateCommand extends Command
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
