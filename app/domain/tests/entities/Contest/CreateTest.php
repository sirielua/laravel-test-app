<?php

namespace App\domain\tests\entities\Contest;

use PHPUnit\Framework\TestCase;

use App\domain\entities\Contest\Contest;
use App\domain\entities\Contest\Id;
use App\domain\entities\Contest\Status;
use App\domain\entities\Contest\Description;
use App\domain\entities\Contest\Banner;

use App\domain\entities\Contest\events\ContestCreated;

class CreateTest extends TestCase
{
    public function testCreate()
    {
        $contest = new Contest(
            $id = Id::next(),
            $status = new Status(Status::ACTIVE),
            $description = new Description(
                'Test Contest Header',
                'Test Contest Subheader',
                'Some explaining text',
                new Banner('/path-to-imaginary-banner/image.jpg')
            )
        );

        $this->assertNotEmpty($events = $contest->releaseEvents());
        $this->assertInstanceOf(ContestCreated::class, end($events));

        $this->assertEquals($id, $contest->getId());
        $this->assertEquals($status, $contest->getStatus());
        $this->assertEquals($description, $contest->getDescription());

        $this->assertTrue($contest->isActive());
    }
}
