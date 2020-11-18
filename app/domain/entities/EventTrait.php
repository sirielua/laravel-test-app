<?php

namespace App\domain\entities;

trait EventTrait
{
    private $events = [];

    public function addEvent($event): void
    {
        $this->events[] = $event;
    }

    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }
}
