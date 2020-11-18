<?php

namespace App\domain\tests\entities\Participant;

use PHPUnit\Framework\TestCase;

use App\domain\entities\Participant\FacebookId;
use App\domain\entities\Participant\events\ParticipantFacebookIdAttached;

class AttachFacebookIdTest extends TestCase
{
    public function testFacebookId(): void
    {
        $participant = (new ParticipantBuilder)->build();

        $participant->attachFacebookId($fb = new FacebookId(\uniqid()));

        $this->assertNotEmpty($events = $participant->releaseEvents());
        $this->assertInstanceOf(ParticipantFacebookIdAttached::class, end($events));
        $this->assertEquals($fb, $participant->getFacebookId());
    }
}
