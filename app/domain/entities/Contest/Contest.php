<?php

namespace App\domain\entities\Contest;

use App\domain\entities\AggregateRoot;
use App\domain\entities\EventTrait;

class Contest implements AggregateRoot
{
    use EventTrait;

    private $id;
    private $status;
    private $description;

    public function __construct(Id $id, Status $status, Description $description)
    {
        $this->id = $id;
        $this->status = $status;
        $this->description = $description;

        $this->addEvent(new events\ContestCreated($this->id));
    }

    public function activate(): void
    {
        $this->changeStatus(new Status(Status::ACTIVE));
    }

    public function deactivate(): void
    {
        $this->changeStatus(new Status(Status::INACTIVE));
    }

    private function changeStatus(Status $status)
    {
        if (!$this->status->isEqualTo($status)) {
            $this->addEvent(new events\ContestStatusChanged($this->id, $status));
        }

        $this->status = $status;
    }

    public function changeDescription(Description $description): void
    {
        if (!$this->description->isEqualTo($description)) {
            $this->addEvent(new events\ContestDescriptionChanged($this->id));
        }

        $this->description = $description;
    }

    public function remove(): void
    {
        if ($this->isActive()) {
            throw new \DomainException('Cannot remove active contest.');
        }

        $this->addEvent(new events\ContestRemoved($this->id));
    }

    public function getId(): id
    {
        return $this->id;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getDescription(): Description
    {
        return $this->description;
    }

    public function isActive(): bool
    {
        return $this->status->getStatus() === Status::ACTIVE;
    }
}
