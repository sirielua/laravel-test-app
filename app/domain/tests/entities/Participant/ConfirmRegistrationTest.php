<?php

namespace App\domain\tests\entities\Participant;

use PHPUnit\Framework\TestCase;

use App\domain\entities\Participant\events\ParticipantRegistrationConfirmationAttempt;
use App\domain\entities\Participant\events\ParticipantRegistrationConfirmed;

class ConfirmRegistrationTest extends TestCase
{
    public function testConfirmationAttempt(): void
    {
        $participant = (new ParticipantBuilder())
            ->unconfirmed()
            ->build();

        $participant->registerConfirmationAttempt();
        $participant->registerConfirmationAttempt();

        $this->assertNotEmpty($events = $participant->releaseEvents());
        $this->assertInstanceOf(ParticipantRegistrationConfirmationAttempt::class, end($events));
        $this->assertEquals(2, $participant->getRegistrationData()->getConfirmationAttempts());
        $this->assertInstanceOf(\DateTimeImmutable::class, $participant->getRegistrationData()->getLastConfirmationAttemptAt());
    }

    public function testConfirmationAttemptAlreadyConfirmed(): void
    {
        $this->expectException(\DomainException::class);
        $participant = (new ParticipantBuilder())->build();

        $participant->registerConfirmationAttempt();
    }

    public function testConfirm(): void
    {
        $participant = (new ParticipantBuilder())
            ->unconfirmed()
            ->build();

        $participant->confirmRegistration();

        $this->assertNotEmpty($events = $participant->releaseEvents());
        $this->assertInstanceOf(ParticipantRegistrationConfirmed::class, end($events));
        $this->assertTrue($participant->getIsRegistrationConfirmed());
        $this->assertTrue($participant->getRegistrationData()->getIsRegistrationConfirmed());
        $this->assertInstanceOf(\DateTimeImmutable::class, $participant->getRegistrationData()->getConfirmedAt());
    }

    public function testToConfirmAlreadyConfirmed(): void
    {
        $this->expectException(\DomainException::class);
        $participant = (new ParticipantBuilder())->build();

        $participant->confirmRegistration();
    }
}
