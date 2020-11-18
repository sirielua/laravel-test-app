<?php

namespace App\domain\dispatchers;

interface EventDispatcher
{
    public function dispatch(array $events): void;
}
