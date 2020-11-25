<?php

namespace App\domain\tests\repositories\Participant;

use App\domain\tests\entities\Participant\ParticipantBuilder;
use App\domain\entities\Participant\Participant;
use App\domain\entities\Participant\Id;
use App\domain\entities\Participant\Phone;
use App\domain\entities\Participant\FacebookId;
use App\domain\repositories\NotFoundException;
use App\domain\repositories\DuplicateKeyException;

trait ParticipantRepositoryTest
{
    protected static $repository;

    public function testAdd(): void
    {
        $participant = (new ParticipantBuilder())
            ->withReferralId(Id::next())
            ->build();

        self::$repository->add($participant);
        $found = self::$repository->get($participant->getId());

        $this->guardEquals($participant, $found);
    }

    private function guardEquals(Participant $created, Participant $fetched): void
    {
        $this->assertEquals($fetched->getId(), $created->getId());
        $this->assertEquals($fetched->getContestId(), $created->getContestId());
        $this->assertEquals($fetched->getName(), $created->getName());
        $this->assertEquals($fetched->getPhone(), $created->getPhone());
        $this->assertEquals($fetched->getRegisteredAt(), $created->getRegisteredAt());
        $this->assertEquals($fetched->getReferralId(), $created->getReferralId());
        $this->assertEquals($fetched->getReferralQuantity(), $created->getReferralQuantity());
        $this->assertEquals($fetched->getIsRegistrationConfirmed(), $created->getIsRegistrationConfirmed());
        $this->assertEquals($fetched->getFacebookId(), $created->getFacebookId());

        $this->assertEquals($fetched->getRegistrationData()->getStatus(), $created->getRegistrationData()->getStatus());
        $this->assertEquals($fetched->getRegistrationData()->getRegisteredAt(), $created->getRegistrationData()->getRegisteredAt());
        $this->assertEquals($fetched->getRegistrationData()->getConfirmationCode(), $created->getRegistrationData()->getConfirmationCode());
        $this->assertEquals($fetched->getRegistrationData()->getConfirmationReceivedTimes(), $created->getRegistrationData()->getConfirmationReceivedTimes());
        $this->assertEquals($fetched->getRegistrationData()->getConfirmationReceivedAt(), $created->getRegistrationData()->getConfirmationReceivedAt());
        $this->assertEquals($fetched->getRegistrationData()->getConfirmationAttempts(), $created->getRegistrationData()->getConfirmationAttempts());
        $this->assertEquals($fetched->getRegistrationData()->getLastConfirmationAttemptAt(), $created->getRegistrationData()->getLastConfirmationAttemptAt());
        $this->assertEquals($fetched->getRegistrationData()->getConfirmedAt(), $created->getRegistrationData()->getConfirmedAt());
        $this->assertEquals($fetched->getRegistrationData()->getIsRegistrationConfirmed(), $created->getRegistrationData()->getIsRegistrationConfirmed());
    }

    public function testAddDuplicatedId(): void
    {
        $this->expectException(DuplicateKeyException::class);
        $participant = (new ParticipantBuilder())
            ->withId(Id::next())
            ->build();

        self::$repository->add($participant);
        self::$repository->add($participant);
    }

    public function testSave(): void
    {
        $participant = (new ParticipantBuilder())
            ->unconfirmed()
            ->withReferralId(Id::next())
            ->build();
        self::$repository->add($participant);

        $participant->setRegistrationConfirmationCode('new-code');
        $participant->sendRegistrationConfirmationMessage();
        $participant->registerConfirmationAttempt();
        $participant->confirmRegistration();
        $participant->setReferralQuantity(5);
        $participant->attachFacebookId(new FacebookId(\uniqid()));

        self::$repository->save($participant);
        $found = self::$repository->get($participant->getId());

        $this->guardEquals($participant, $found);
    }

    public function testGet(): void
    {
        $participant = (new ParticipantBuilder())->build();
        self::$repository->add($participant);

        $found = self::$repository->get($participant->getId());

        $this->assertEquals($participant->getId(), $found->getId());
    }

    public function testGetNotFound(): void
    {
        $this->expectException(NotFoundException::class);

        self::$repository->get(new Id(uniqid()));
    }

    public function testRemove(): void
    {
        $this->expectException(NotFoundException::class);
        $participant = (new ParticipantBuilder())->build();
        self::$repository->add($participant);

        self::$repository->remove($participant);
        self::$repository->get($participant->getId());
    }

    public function testExistsByPhone(): void
    {
        $participant = (new ParticipantBuilder())
            ->withPhone($phone = new Phone('123456789'))
            ->build();
        self::$repository->add($participant);

        $this->assertTrue(self::$repository->existsByPhone($phone));
    }

    public function testDontExistsByPhone(): void
    {
        $phone = new Phone('non-existing-number');

        $this->assertFalse(self::$repository->existsByPhone($phone));
    }

    public function testGetReferralQuantity(): void
    {
        $participant = (new ParticipantBuilder())
            ->withId($referralId = Id::next())
            ->build();
        self::$repository->add($participant);

        for ($i = 0; $i < 5; $i++) {
            $referral = (new ParticipantBuilder())
                ->withReferralId($referralId)
                ->build();
            self::$repository->add($referral);
        }

        $this->assertEquals(5, self::$repository->getReferralQuantity($referralId));
    }

    public function testUnconfirmedReferralsDoesNotQuantity(): void
    {
        $participant = (new ParticipantBuilder())
            ->withId($referralId = Id::next())
            ->build();
        self::$repository->add($participant);

        $unconfirmedReferral = (new ParticipantBuilder())
                ->unconfirmed()
                ->withReferralId($referralId)
                ->build();
        self::$repository->add($unconfirmedReferral);

        $this->assertEquals(0, self::$repository->getReferralQuantity($referralId));
    }
}
