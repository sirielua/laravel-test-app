<?php

namespace App\domain\tests\service\Participant;

use PHPUnit\Framework\TestCase;

use App\domain\service\Participant\Register\RegisterCommand;
use App\domain\service\Participant\Register\RegisterHandler;

use App\domain\repositories\Participant\MemoryParticipantRepository;
use App\domain\dispatchers\DummyEventDispatcher;
use App\domain\components\ConfirmationCodeGenerator\NumberConfirmationCodeGenerator;
use App\domain\service\Participant\Register\exceptions\PhoneAlreadyRegisteredException;

use App\domain\tests\entities\Participant\ParticipantBuilder;
use App\domain\entities\Participant\Id;
use App\domain\entities\Participant\RegistrationStatus;

class RegisterTest extends TestCase
{
    private static $participants;
    private static $dispatcher;
    private static $codeGenerator;

    public static function setUpBeforeClass(): void
    {
        self::$participants = new MemoryParticipantRepository();
        self::$dispatcher = new DummyEventDispatcher();
        self::$codeGenerator = new NumberConfirmationCodeGenerator();
    }

    public function testRegistrationWithoutReferralId()
    {
        $command = new RegisterCommand(
            $contestId = 'contest-id',
            $firstName = 'Sarrah',
            $lastName = 'Connor',
            $phone = uniqid(),
        );
        $handler = new RegisterHandler(self::$codeGenerator, self::$participants, self::$dispatcher);

        $id = $handler->handle($command);
        $participant = self::$participants->get($id);

        $this->assertInstanceOf(Id::class, $id);
        $this->assertEquals($contestId, (string)$participant->getContestId());
        $this->assertEquals($firstName, $participant->getName()->getFirstName());
        $this->assertEquals($lastName, $participant->getName()->getLastName());
        $this->assertEquals($phone, (string)$participant->getPhone());
        $this->assertFalse($participant->getIsRegistrationConfirmed());
        $this->assertInstanceOf(\DateTimeImmutable::class, $participant->getRegisteredAt());
        $this->assertNull($participant->getFacebookId());
        $this->assertNull($participant->getReferralId());
        $this->assertEquals(0, $participant->getReferralQuantity());

        $registrationData = $participant->getRegistrationData();
        $this->assertFalse($registrationData->getIsRegistrationConfirmed());
        $this->assertTrue($registrationData->getStatus()->isEqualTo(new RegistrationStatus(RegistrationStatus::UNCONFIRMED)));
        $this->assertInstanceOf(\DateTimeImmutable::class, $registrationData->getRegisteredAt());
        $this->assertTrue(is_string($registrationData->getConfirmationCode()));
        $this->assertTrue(strlen((string)$registrationData->getConfirmationCode()) > 3);
        $this->assertEquals(0, $registrationData->getConfirmationReceivedTimes());
        $this->assertNull($registrationData->getConfirmationReceivedAt());
        $this->assertEquals(0, $registrationData->getConfirmationAttempts());
        $this->assertNull($registrationData->getLastConfirmationAttemptAt());
        $this->assertNull($registrationData->getConfirmedAt());
    }

    public function testRegistrationWithReferralId()
    {
        $referralParticipant = (new ParticipantBuilder)
            ->withId($referralId = new Id(uniqid()))
            ->build();
        self::$participants->add($referralParticipant);
        $command = new RegisterCommand(
            'contest-id', // contest id
            'Sarrah', // first name
            'Connor', // last name
            $phone = uniqid(),
            (string)$referralId,
        );
        $handler = new RegisterHandler(self::$codeGenerator, self::$participants, self::$dispatcher);

        $id = $handler->handle($command);
        $participant = self::$participants->get($id);

        $this->assertEquals(new Id($referralId), $participant->getReferralId());
    }

    public function testRegistrationWithInvalidReferralId()
    {
        $command = new RegisterCommand(
            'contest-id', // contest id
            'Sarrah', // first name
            'Connor', // last name
            uniqid(), // phone
            uniqid(), // referral id
        );
        $handler = new RegisterHandler(self::$codeGenerator, self::$participants, self::$dispatcher);

        $id = $handler->handle($command);
        $participant = self::$participants->get($id);

        $this->assertNull($participant->getReferralId());
    }

    public function testRegistrationWithExistingPhone()
    {
        $this->expectException(PhoneAlreadyRegisteredException::class);

        $command = new RegisterCommand(
            'contest-id', // contest id
            'Sarrah', // first name
            'Connor', // last name
            $phone = uniqid(),
        );
        $handler = new RegisterHandler(self::$codeGenerator, self::$participants, self::$dispatcher);
        $handler->handle($command);
        $handler->handle($command);
    }
}
