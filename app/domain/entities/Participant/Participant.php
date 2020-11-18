<?php

namespace App\domain\entities\Participant;

use App\domain\entities\AggregateRoot;
use App\domain\entities\EventTrait;

use App\domain\entities\Contest\Id as ContestId;

class Participant implements AggregateRoot
{
    use EventTrait;

    private $id;
    private $contestId;
    private $name;
    private $phone;
    private $registrationData;
    private $referralId;
    private $referralCount;
    private $facebookId;

    public function __construct(Id $id, ContestId $contestId, Name $name, Phone $phone, RegistrationData $registrationData, Id $referralId = null)
    {
        $this->id = $id;
        $this->contestId = $contestId;
        $this->name = $name;
        $this->phone = $phone;
        $this->registrationData = $registrationData;
        $this->referralId = $referralId;
        $this->referralCount = 0;

        $this->addEvent(new events\ParticipantRegistered($this->id));
    }

    public function setRegistrationConfirmationCode(string $code): void
    {
        $this->registrationData->setConfirmationCode($code);
    }

    public function sendRegistrationConfirmationMessage(): void
    {
        if ($this->getIsRegistrationConfirmed()) {
            throw new \DomainException('Confirmation can\'t be send. Registration is already confirmed.');
        }

        $this->registrationData->sendConfirmation();

        $this->addEvent(new events\ParticipantRegistrationConfirmationSend($this->id));
    }

    public function registerConfirmationAttempt(): void
    {
        if ($this->getIsRegistrationConfirmed()) {
            throw new \DomainException('Registration is already confirmed.');
        }

        $this->registrationData->registerConfirmationAttempt();

        $this->addEvent(new events\ParticipantRegistrationConfirmationAttempt($this->id));
    }

    public function confirmRegistration(): void
    {
        if ($this->getIsRegistrationConfirmed()) {
            throw new \DomainException('Registration is already confirmed.');
        }

        $this->registrationData->confirm();

        $this->addEvent(new events\ParticipantRegistrationConfirmed($this->id));
    }

    public function incrementReferralCount(): void
    {
        $this->referralCount++;

        $this->addEvent(new events\ParticipantReferralCountChanged($this->id));
    }

    public function setReferralCount(int $count): void
    {
        $this->referralCount = $count;

        $this->addEvent(new events\ParticipantReferralCountChanged($this->id));
    }

    public function attachFacebookId(FacebookId $facebookId): void
    {
        $this->facebookId = $facebookId;

        $this->addEvent(new events\ParticipantFacebookIdAttached($this->id));
    }

    public function remove(): void
    {
        $this->addEvent(new events\ParticipantRemoved($this->id));
    }

    public function getId(): id
    {
        return $this->id;
    }

    public function getContestId(): Contestid
    {
        return $this->contestId;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getPhone(): Phone
    {
        return $this->phone;
    }

    public function getFacebookId()
    {
        return $this->facebookId;
    }

    public function getReferralId()
    {
        return $this->referralId;
    }

    public function getReferralCount(): int
    {
        return $this->referralCount;
    }

    public function getRegistrationData(): RegistrationData
    {
        return $this->registrationData;
    }

    public function getIsRegistrationConfirmed(): bool
    {
        return $this->registrationData->getIsRegistrationConfirmed();
    }

    public function getRegisteredAt(): \DateTimeImmutable
    {
        return $this->registrationData->getRegisteredAt();
    }
}
