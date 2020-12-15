<?php

namespace App\Events;

trait DomainEventForwarder
{
    private $domainEvent;

    public function __construct($domainEvent)
    {
        $this->domainEvent = $domainEvent;
    }

    public function getDomainEvent()
    {
        return $this->domainEvent;
    }
}
