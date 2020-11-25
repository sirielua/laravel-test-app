<?php

namespace App\domain\tests\entities\Participant;

use PHPUnit\Framework\TestCase;

use App\domain\entities\Participant\Id;
use App\domain\entities\Participant\events\ParticipantReferralQuantityChanged;

class ReferralQuantityTest extends TestCase
{
    public function testSetReferralQuantity(): void
    {
        $participant = (new ParticipantBuilder())
            ->withReferralId($referral = Id::next())
            ->build();

        $participant->setReferralQuantity(5);

        $this->assertNotEmpty($events = $participant->releaseEvents());
        $this->assertInstanceOf(ParticipantReferralQuantityChanged::class, end($events));
        $this->assertEquals(5, $participant->getReferralQuantity());
    }
}
