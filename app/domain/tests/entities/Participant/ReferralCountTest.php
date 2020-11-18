<?php

namespace App\domain\tests\entities\Participant;

use PHPUnit\Framework\TestCase;

use App\domain\entities\Participant\Id;
use App\domain\entities\Participant\events\ParticipantReferralCountChanged;

class ReferralCOuntTes extends TestCase
{
    public function testIncrementReferralCount(): void
    {
        $participant = (new ParticipantBuilder())
            ->withReferralId($referral = Id::next())
            ->build();

        $participant->incrementReferralCount();
        $participant->incrementReferralCount();

        $this->assertNotEmpty($events = $participant->releaseEvents());
        $this->assertInstanceOf(ParticipantReferralCountChanged::class, end($events));    
        $this->assertEquals(2, $participant->getReferralCount());
    }
    
    public function testSetReferralCount(): void
    {
        $participant = (new ParticipantBuilder())
            ->withReferralId($referral = Id::next())
            ->build();

        $participant->setReferralCount(5);

        $this->assertNotEmpty($events = $participant->releaseEvents());
        $this->assertInstanceOf(ParticipantReferralCountChanged::class, end($events));    
        $this->assertEquals(5, $participant->getReferralCount());
    }
}
