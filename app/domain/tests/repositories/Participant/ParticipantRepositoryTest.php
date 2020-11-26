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
        $this->assertEquals($fetched->getRegisteredAt()->getTimestamp(), $created->getRegisteredAt()->getTimestamp());
        $this->assertEquals($fetched->getReferralId(), $created->getReferralId());
        $this->assertEquals($fetched->getReferralQuantity(), $created->getReferralQuantity());
        $this->assertEquals($fetched->getIsRegistrationConfirmed(), $created->getIsRegistrationConfirmed());
        $this->assertEquals($fetched->getFacebookId(), $created->getFacebookId());

        $fRegData = $fetched->getRegistrationData();
        $cRegData = $created->getRegistrationData();

        $this->assertEquals($fRegData->getStatus(), $cRegData->getStatus());
        $this->assertEquals($fRegData->getRegisteredAt()->getTimestamp(), $cRegData->getRegisteredAt()->getTimestamp());
        $this->assertEquals($fRegData->getConfirmationCode(), $cRegData->getConfirmationCode());
        $this->assertEquals($fRegData->getConfirmationReceivedTimes(), $cRegData->getConfirmationReceivedTimes());
        $this->guardDatetime($fRegData->getConfirmationReceivedAt(), $cRegData->getConfirmationReceivedAt());
        $this->assertEquals($fRegData->getConfirmationAttempts(), $cRegData->getConfirmationAttempts());
        $this->guardDatetime($fRegData->getLastConfirmationAttemptAt(), $cRegData->getLastConfirmationAttemptAt());
        $this->guardDatetime($fRegData->getConfirmedAt(), $cRegData->getConfirmedAt());
        $this->assertEquals($fRegData->getIsRegistrationConfirmed(), $cRegData->getIsRegistrationConfirmed());
    }

    private function guardDatetime(\DateTimeImmutable $created = null, \DateTimeImmutable $fetched = null)
    {
        if ($created) {
            $this->assertEquals($created->getTimestamp(), $fetched->getTimestamp());
        } else {
            $this->assertNull($created);
            $this->assertNull($fetched);
        }
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
