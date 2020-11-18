<?php

namespace App\domain\tests\entities\Participant;

use PHPUnit\Framework\TestCase;

use App\domain\entities\Participant\events\ParticipantRemoved;

class RemoveTest extends TestCase
{
    public function testRemove()
    {
        $participant = (new ParticipantBuilder())->build();

        $participant->remove();

        $this->assertNotEmpty($events = $participant->releaseEvents());
        $this->assertInstanceOf(ParticipantRemoved::class, end($events));
    }
}
