<?php

namespace App\domain\tests\service\Participant;

use PHPUnit\Framework\TestCase;

use App\domain\service\Participant\ConfirmRegistration\ConfirmRegistrationCommand;
use App\domain\service\Participant\ConfirmRegistration\ConfirmRegistrationHandler;

use App\domain\repositories\Participant\MemoryParticipantRepository;
use App\domain\dispatchers\DummyEventDispatcher;
use App\domain\tests\entities\Participant\ParticipantBuilder;
use App\domain\entities\Participant\Id;
use App\domain\entities\Participant\RegistrationStatus;
use App\domain\service\Participant\ConfirmRegistration\exceptions\InvalidConfirmationCodeException;
use App\domain\service\Participant\ConfirmRegistration\exceptions\RegistrationAlreadyConfirmedException;

class ConfirmRegistrationTest extends TestCase
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
        $command = new ConfirmRegistrationCommand((string)$id, $code);
        $handler = new ConfirmRegistrationHandler(self::$participants, self::$dispatcher);

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

    public function testConfirmWithInvalidCode()
    {
        $this->expectException(InvalidConfirmationCodeException::class);

        $participant = (new ParticipantBuilder())
            ->withId($id = Id::next())
            ->unconfirmed()
            ->withConfirmationCode($code = '12345')
            ->build();
        self::$participants->add($participant);
        $command = new ConfirmRegistrationCommand((string)$id, 'wrong-code');
        $handler = new ConfirmRegistrationHandler(self::$participants, self::$dispatcher);

        $handler->handle($command);
        $found = self::$participants->get($participant->getId());

        $registrationData = $found->getRegistrationData();
        $this->assertEquals(1, $registrationData->getConfirmationAttempts());
    }

    public function testConfirmationMultipleAttempts()
    {
        $participant = (new ParticipantBuilder())
            ->withId($id = Id::next())
            ->unconfirmed()
            ->withConfirmationCode($code = '12345')
            ->build();
        self::$participants->add($participant);
        $commandWithInvalidCode = new ConfirmRegistrationCommand((string)$id, 'wrong-code');
        $commandWithValidCode = new ConfirmRegistrationCommand((string)$id, $code);
        $handler = new ConfirmRegistrationHandler(self::$participants, self::$dispatcher);

        try {
            $handler->handle($commandWithInvalidCode);
        } catch (InvalidConfirmationCodeException $e) {
            $handler->handle($commandWithValidCode);
        }
        $found = self::$participants->get($participant->getId());

        $registrationData = $found->getRegistrationData();
        $this->assertEquals(2, $registrationData->getConfirmationAttempts());
    }

    public function testConfirmConfirmed()
    {
        $this->expectException(RegistrationAlreadyConfirmedException::class);

        $participant = (new ParticipantBuilder())
            ->withId($id = Id::next())
            ->withConfirmationCode($code = '12345')
            ->build();
        self::$participants->add($participant);
        $command = new ConfirmRegistrationCommand((string)$id, $code);
        $handler = new ConfirmRegistrationHandler(self::$participants, self::$dispatcher);

        $handler->handle($command);
    }
}
