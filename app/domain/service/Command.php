<?php

namespace App\domain\service;

abstract class Command
{
    private $isHandled = false;

    public function __get($name): mixed
    {
        $getter = 'get' . \ucfirst($name);
        if (\method_exists($this, $getter)) {
            return $this->$getter();
        }
    }

    public function isHandled(): bool
    {
        return $this->isHandled;
    }

    public function setHandled(): void
    {
        $this->isHandled = true;
    }
}
