<?php

namespace App\domain\entities\Participant;

use Assert\Assertion;

class RegistrationData
{
    private $status;
    private $registeredAt;
    private $confirmationCode;
    private $confirmationReceived;
    private $confirmationAttempts;
    private $confirmationReceivedAt;
    private $lastConfirmationAttemptAt;
    private $confirmedAt;

    public function __construct(
        RegistrationStatus $status,
        \DateTimeImmutable $registeredAt,
        string $confirmationCode,
        int $confirmationReceived = 0,
        int $confirmationAttempts = 0,
        \DateTimeImmutable $confirmationReceivedAt = null,
        \DateTimeImmutable $lastConfirmationAttemptAt = null,
        \DateTimeImmutable $confirmedAt = null
    ) {
        Assertion::greaterOrEqualThan(\strlen($confirmationCode), 4);
        Assertion::greaterOrEqualThan($confirmationReceived, 0);
        Assertion::greaterOrEqualThan($confirmationAttempts, 0);

        $this->status = $status;
        $this->registeredAt = $registeredAt;
        $this->confirmationCode = $confirmationCode;
        $this->confirmationReceived = $confirmationReceived;
        $this->confirmationAttempts = $confirmationAttempts;
        $this->confirmationReceivedAt = $confirmationReceivedAt;
        $this->lastConfirmationAttemptAt = $lastConfirmationAttemptAt;
        $this->confirmedAt = $confirmedAt;
    }

    public function setConfirmationCode(string $code): void
    {
        $this->confirmationCode = $code;
    }

    public function sendConfirmation(): void
    {
        if (!$this->confirmationCode) {
            throw new \DomainException('Confirmation code can\'t be blank');
        }

        $this->confirmationReceived++;
        $this->confirmationAttempts = 0;
        $this->confirmationReceivedAt = new \DateTimeImmutable;
        $this->lastConfirmationAttemptAt = new \DateTimeImmutable;
    }

    public function registerConfirmationAttempt(): void
    {
        $this->confirmationAttempts++;
        $this->lastConfirmationAttemptAt = new \DateTimeImmutable;
    }

    public function confirm(): void
    {
        $this->confirmedAt = new \DateTimeImmutable;
        $this->status = new RegistrationStatus(RegistrationStatus::CONFIRMED);
    }

    public function getStatus(): RegistrationStatus
    {
        return $this->status;
    }

    public function getRegisteredAt(): \DateTimeImmutable
    {
        return $this->registeredAt;
    }

    public function getConfirmationCode(): string
    {
        return $this->confirmationCode;
    }

    public function getConfirmationReceivedTimes(): int
    {
        return $this->confirmationReceived;
    }

    public function getConfirmationReceivedAt()
    {
        return $this->confirmationReceivedAt;
    }

    public function getConfirmationAttempts(): int
    {
        return $this->confirmationAttempts;
    }

    public function getLastConfirmationAttemptAt()
    {
        return $this->lastConfirmationAttemptAt;
    }

    public function getConfirmedAt()
    {
        return $this->confirmedAt;
    }

    public function getIsRegistrationConfirmed(): bool
    {
        return $this->status->getStatus() === RegistrationStatus::CONFIRMED;
    }
}
