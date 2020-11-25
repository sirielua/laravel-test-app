<?php

namespace App\domain\tests\service\Participant;

use PHPUnit\Framework\TestCase;

use App\domain\components\RegistrationNotifier\DummyRegistrationNotifier;
use App\domain\service\Participant\SendConfirmation\SendConfirmationCommand;
use App\domain\service\Participant\SendConfirmation\SendConfirmationHandler;

use App\domain\repositories\Participant\MemoryParticipantRepository;
use App\domain\dispatchers\DummyEventDispatcher;
use App\domain\tests\entities\Participant\ParticipantBuilder;
use App\domain\entities\Participant\Id;
use App\domain\service\Participant\SendConfirmation\exceptions\OnlyUnconfirmedParticipantsCouldBeNotifiedException;
use App\domain\repositories\NotFoundException;

class SendConfirmationTest extends TestCase
{
    private static $notifier;
    private static $participants;
    private static $dispatcher;

    public static function setUpBeforeClass(): void
    {
        self::$notifier = new DummyRegistrationNotifier();
        self::$participants = new MemoryParticipantRepository();
        self::$dispatcher = new DummyEventDispatcher();
    }

    public function testSendConfirmation()
    {
        $participant = (new ParticipantBuilder())
            ->withId($id = Id::next())
            ->unconfirmed()
            ->build();
        self::$participants->add($participant);

        $command = new SendConfirmationCommand((string)$id);
        $handler = new SendConfirmationHandler(self::$notifier, self::$participants, self::$dispatcher);

        $handler->handle($command);
        $handler->handle($command);
        $found = self::$participants->get($id);

        $this->assertFalse($found->getIsRegistrationConfirmed());
        $registrationData = $participant->getRegistrationData();
        $this->assertEquals(2, $registrationData->getConfirmationReceivedTimes());
        $this->assertInstanceOf(\DateTimeImmutable::class, $registrationData->getConfirmationReceivedAt());
    }

    public function testSendConfirmationToAlreadyConfirmed()
    {
        $this->expectException(OnlyUnconfirmedParticipantsCouldBeNotifiedException::class);

        $participant = (new ParticipantBuilder())
            ->withId($id = Id::next())
            ->build();
        self::$participants->add($participant);

        $command = new SendConfirmationCommand((string)$id);
        $handler = new SendConfirmationHandler(self::$notifier, self::$participants, self::$dispatcher);

        $handler->handle($command);
    }

    public function testSendConfirmationToNonExistentParticipant()
    {
        $this->expectException(NotFoundException::class);

        $command = new SendConfirmationCommand(uniqid());
        $handler = new SendConfirmationHandler(self::$notifier, self::$participants, self::$dispatcher);

        $handler->handle($command);
    }
}
