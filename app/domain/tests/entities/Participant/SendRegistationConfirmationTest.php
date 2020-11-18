<?php

namespace App\domain\tests\entities\Participant;

use PHPUnit\Framework\TestCase;

use App\domain\entities\Participant\events\ParticipantRegistrationConfirmationSend;

class SendRegistationConfirmationTest extends TestCase
{
    public function testSetConfirmationCode(): void
    {
        $participant = (new ParticipantBuilder())->build();

        $participant->setRegistrationConfirmationCode($code = 'confirmation-code');

        $this->assertEquals($code, $participant->getRegistrationData()->getConfirmationCode());
    }

    public function testSendConfirmation(): void
    {
        $participant = (new ParticipantBuilder())
            ->unconfirmed()
            ->withConfirmationCode($code = 'confirmation-code')
            ->build();

        $participant->sendRegistrationConfirmationMessage();
        $participant->sendRegistrationConfirmationMessage();

        $this->assertNotEmpty($events = $participant->releaseEvents());
        $this->assertInstanceOf(ParticipantRegistrationConfirmationSend::class, end($events));
        $this->assertEquals(2, $participant->getRegistrationData()->getConfirmationReceivedTimes());
        $this->assertInstanceOf(\DateTimeImmutable::class, $participant->getRegistrationData()->getConfirmationReceivedAt());
    }

    public function testSendToAlreadyConfirmedParticipant(): void
    {
        $this->expectException(\DomainException::class);
        $participant = (new ParticipantBuilder())->build();

        $participant->sendRegistrationConfirmationMessage();
    }
}
