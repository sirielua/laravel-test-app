<?php

namespace App\domain\tests\entities\Contest;

use PHPUnit\Framework\TestCase;

use App\domain\entities\Contest\Status;
use App\domain\entities\Contest\events\ContestStatusChanged;

class StatusTest extends TestCase
{
    public function testActivate()
    {
        $contest = (new ContestBuilder())->withStatus(new Status(Status::INACTIVE))->build();

        $contest->activate();
        
        $this->assertNotEmpty($events = $contest->releaseEvents());
        $this->assertInstanceOf(ContestStatusChanged::class, end($events));
        $this->assertTrue($contest->isActive());
    }

    public function testActivateActive()
    {
        $contest = (new ContestBuilder())->withStatus(new Status(Status::ACTIVE))->build();
        $contest->releaseEvents();

        $contest->activate();

        $this->assertEmpty($events = $contest->releaseEvents());
        $this->assertTrue($contest->isActive());
    }

    public function testDeactivate()
    {
        $contest = (new ContestBuilder())->withStatus(new Status(Status::ACTIVE))->build();

        $contest->deactivate();
        
        $this->assertNotEmpty($events = $contest->releaseEvents());
        $this->assertInstanceOf(ContestStatusChanged::class, end($events));
        $this->assertFalse($contest->isActive());
    }

    public function testDeactivateInactive()
    {
        $contest = (new ContestBuilder())->withStatus(new Status(Status::INACTIVE))->build();
        $contest->releaseEvents();

        $contest->deactivate();

        $this->assertEmpty($events = $contest->releaseEvents());
        $this->assertFalse($contest->isActive());
    }
}
