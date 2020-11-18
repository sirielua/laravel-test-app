<?php

namespace App\domain\entities\base;

use Assert\Assertion;

class File
{
    private $path;

    public function __construct(string $path)
    {
        Assertion::notEmpty($path);

        $this->path = $path;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
