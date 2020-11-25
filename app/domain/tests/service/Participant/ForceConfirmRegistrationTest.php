<?php

namespace App\domain\tests\service\Participant;

use PHPUnit\Framework\TestCase;

use App\domain\service\Participant\ForceConfirmRegistration\ForceConfirmRegistrationCommand;
use App\domain\service\Participant\ForceConfirmRegistration\ForceConfirmRegistrationHandler;

use App\domain\repositories\Participant\MemoryParticipantRepository;
use App\domain\dispatchers\DummyEventDispatcher;
use App\domain\tests\entities\Participant\ParticipantBuilder;
use App\domain\entities\Participant\Id;
use App\domain\entities\Participant\RegistrationStatus;

class ForceConfirmRegistrationTest extends TestCase
{
    private static $participants;
    private static $dispatcher;

    public static function setUpBeforeClass(): void
    {
        self::$participants = new MemoryParticipantRepository();
        self::$dispatcher = new DummyEventDispatcher();
    }

    public function testConfirmRegistration()
    {
        $participant = (new ParticipantBuilder())
            ->withId($id = Id::next())
            ->unconfirmed()
            ->withConfirmationCode($code = '12345')
            ->build();
        self::$participants->add($participant);
        $command = new ForceConfirmRegistrationCommand((string)$id);
        $handler = new ForceConfirmRegistrationHandler(self::$participants, self::$dispatcher);

        $handler->handle($command);
        $found = self::$participants->get($participant->getId());

        $this->assertTrue($found->getIsRegistrationConfirmed());
        $registrationData = $found->getRegistrationData();
        $this->assertTrue($registrationData->getIsRegistrationConfirmed());
        $this->assertTrue($registrationData->getStatus()->isEqualTo(new RegistrationStatus(RegistrationStatus::CONFIRMED)));
        $this->assertInstanceOf(\DateTimeImmutable::class, $registrationData->getRegisteredAt());
        $this->assertEquals(1, $registrationData->getConfirmationAttempts());
        $this->assertInstanceOf(\DateTimeImmutable::class, $registrationData->getLastConfirmationAttemptAt());
        $this->assertInstanceOf(\DateTimeImmutable::class, $registrationData->getConfirmedAt());
    }

    public function testConfirmConfirmed()
    {
        $participant = (new ParticipantBuilder())
            ->withId($id = Id::next())
            ->withConfirmationCode($code = '12345')
            ->build();
        self::$participants->add($participant);
        $command = new ForceConfirmRegistrationCommand((string)$id, $code);
        $handler = new ForceConfirmRegistrationHandler(self::$participants, self::$dispatcher);

        $handler->handle($command);
        $found = self::$participants->get($participant->getId());

        $this->assertTrue($found->getIsRegistrationConfirmed());
        $registrationData = $found->getRegistrationData();
        $this->assertTrue($registrationData->getIsRegistrationConfirmed());
        $this->assertTrue($registrationData->getStatus()->isEqualTo(new RegistrationStatus(RegistrationStatus::CONFIRMED)));
        $this->assertInstanceOf(\DateTimeImmutable::class, $registrationData->getRegisteredAt());
        $this->assertEquals(1, $registrationData->getConfirmationAttempts());
        $this->assertInstanceOf(\DateTimeImmutable::class, $registrationData->getLastConfirmationAttemptAt());
        $this->assertInstanceOf(\DateTimeImmutable::class, $registrationData->getConfirmedAt());
    }
}
