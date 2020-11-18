<?php

namespace App\domain\tests\entities\Participant;

use PHPUnit\Framework\TestCase;

use App\domain\entities\Participant\Participant;
use App\domain\entities\Participant\Id;
use App\domain\entities\Contest\Id as ContestId;
use App\domain\entities\Participant\Name;
use App\domain\entities\Participant\Phone;
use App\domain\entities\Participant\RegistrationData;
use App\domain\entities\Participant\RegistrationStatus;
use App\domain\entities\Participant\FacebookId;

use App\domain\entities\Participant\events\ParticipantRegistered;

class CreateTest extends TestCase
{
    public function testCreate(): void
    {
        $participant = new Participant(
            $id = Id::next(),
            $contestId = ContestId::next(),
            $name = new Name('Sarah', 'Connor'),
            $phone = new Phone('123456789'),
            $registrationData = new RegistrationData(
                $registrationStatus = new RegistrationStatus(RegistrationStatus::UNCONFIRMED),
                $registeredAt = new \DateTimeImmutable(),
                $confirmationCode = 12345,
            )
        );

        $this->assertNotEmpty($events = $participant->releaseEvents());
        $this->assertInstanceOf(ParticipantRegistered::class, end($events));

        $this->assertEquals($id, $participant->getId());
        $this->assertEquals($contestId, $participant->getContestId());
        $this->assertEquals($name, $participant->getName());
        $this->assertEquals($phone, $participant->getPhone());
        $this->assertEquals($registeredAt, $participant->getRegisteredAt());
        $this->assertNull($participant->getReferralId());
        $this->assertEquals(0, $participant->getReferralCount());
        $this->assertFalse($participant->getIsRegistrationConfirmed());
        $this->assertNull($participant->getFacebookId());

        $this->assertEquals($registrationStatus, $participant->getRegistrationData()->getStatus());
        $this->assertEquals($registeredAt, $participant->getRegistrationData()->getRegisteredAt());
        $this->assertEquals($confirmationCode, $participant->getRegistrationData()->getConfirmationCode());
        $this->assertEquals(0, $participant->getRegistrationData()->getConfirmationReceivedTimes());
        $this->assertNull($participant->getRegistrationData()->getConfirmationReceivedAt());
        $this->assertEquals(0, $participant->getRegistrationData()->getConfirmationAttempts());
        $this->assertNull($participant->getRegistrationData()->getLastConfirmationAttemptAt());
        $this->assertNull($participant->getRegistrationData()->getConfirmedAt());
        $this->assertFalse($participant->getRegistrationData()->getIsRegistrationConfirmed());
    }

    public function testCreateWithReferralId(): void
    {
        $participant = new Participant(
            $id = Id::next(),
            $contestId = ContestId::next(),
            $name = new Name('Sarah', 'Connor'),
            $phone = new Phone('123456789'),
            $registrationData = new RegistrationData(
                $registrationStatus = new RegistrationStatus(RegistrationStatus::UNCONFIRMED),
                $registeredAt = new \DateTimeImmutable(),
                $confirmationCode = 12345,
            ),
            $referralId = Id::next()
        );

        $this->assertEquals($referralId, $participant->getReferralId());
        $this->assertNotEquals($referralId, $id);
    }
}
