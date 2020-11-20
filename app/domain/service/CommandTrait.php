<?php

namespace App\domain\service;

trait CommandTrait
{
    private $isHandled = false;

    public function __get($name)
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
