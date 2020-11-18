<?php

namespace App\domain\tests\entities\Contest;

use PHPUnit\Framework\TestCase;

use App\domain\entities\Contest\Status;
use App\domain\entities\Contest\events\ContestRemoved;

class RemoveTest extends TestCase
{
    public function testRemove()
    {
        $contest = (new ContestBuilder())
            ->withStatus($status = new Status(Status::INACTIVE))
            ->build();

        $contest->remove();

        $this->assertNotEmpty($events = $contest->releaseEvents());
        $this->assertInstanceOf(ContestRemoved::class, end($events));
    }

    public function testRemoveActive()
    {
        $this->expectException(\DomainException::class);

        $contest = (new ContestBuilder())
            ->withStatus($status = new Status(Status::ACTIVE))
            ->build();

        $contest->remove();
    }
}
