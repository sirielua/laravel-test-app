<?php

namespace App\domain\tests\entities\Participant;

use App\domain\entities\Participant\Participant;
use App\domain\entities\Participant\Id;
use App\domain\entities\Contest\Id as ContestId;
use App\domain\entities\Participant\Name;
use App\domain\entities\Participant\Phone;
use App\domain\entities\Participant\RegistrationData;
use App\domain\entities\Participant\RegistrationStatus;
use App\domain\entities\Participant\FacebookId;

class ParticipantBuilder
{
    private $id;
    private $contestId;
    private $name;
    private $phone;
    private $referralId;
    private $referralCount = 0;
    private $facebookId;
    private $confirmationCode = 'confirmation-code';
    private $isUnconfirmed;

    public function __construct()
    {
        $this->id = Id::next();
        $this->contestId = ContestId::next();
        $this->name = new Name('Sarah', 'Connor');
        $this->phone = new Phone('1234567890');
        $this->referralCount = 0;
        $this->facebookId = new FacebookId('facebook-id');
    }

    public function withId(Id $id): self
    {
        $clone = clone $this;
        $clone->id = $id;
        return $clone;
    }

    public function withContestId(ContestId $contestId): self
    {
        $clone = clone $this;
        $clone->contestId = $contestId;
        return $clone;
    }

    public function withName(Name $name): self
    {
        $clone = clone $this;
        $clone->name = $name;
        return $clone;
    }

    public function withPhone(Phone $phone): self
    {
        $clone = clone $this;
        $clone->phone = $phone;
        return $clone;
    }

    public function withReferralId(Id $id): self
    {
        $clone = clone $this;
        $clone->referralId = $id;
        return $clone;
    }

    public function unconfirmed(): self
    {
        $clone = clone $this;
        $clone->isUnconfirmed = true;
        return $clone;
    }

    public function withConfirmationCode(string $confirmationCode): self
    {
        $clone = clone $this;
        $clone->confirmationCode = $confirmationCode;
        return $clone;
    }

    public function build(): Participant
    {
        return new Participant(
            $this->id,
            $this->contestId,
            $this->name,
            $this->phone,
            $this->buildRegistrationData(),
            $this->referralId
        );
    }

    private function buildRegistrationData(): RegistrationData
    {
        if ($this->isUnconfirmed) {
            $status = new RegistrationStatus(RegistrationStatus::UNCONFIRMED);
            $confirmationReceived = 0;
            $confirmationAttempts = 0;
            $confirmationReceivedAt = null;
            $lastConfirmationAttemptAt = null;
            $confirmedAt = null;
        } else {
            $status = new RegistrationStatus(RegistrationStatus::CONFIRMED);
            $confirmationReceived = 1;
            $confirmationAttempts = 1;
            $confirmationReceivedAt = new \DateTimeImmutable;
            $lastConfirmationAttemptAt = new \DateTimeImmutable;
            $confirmedAt = new \DateTimeImmutable;
        }

        return new RegistrationData(
            $status,
            $registeredAt = new \DateTimeImmutable,
            $this->confirmationCode,
            $confirmationReceived,
            $confirmationAttempts,
            $confirmationReceivedAt,
            $lastConfirmationAttemptAt,
            $confirmedAt
        );
    }
}
