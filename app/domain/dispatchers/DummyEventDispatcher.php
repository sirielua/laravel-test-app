<?php

namespace App\domain\dispatchers;

class DummyEventDispatcher implements EventDispatcher
{
    public function dispatch(array $events = []): void
    {
        foreach ($events as $event) {
            // do nothing
        }
    }
}
