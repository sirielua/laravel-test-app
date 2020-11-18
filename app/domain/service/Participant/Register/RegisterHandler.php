<?php

namespace App\domain\service\Participant\Register;

use App\domain\service\CommandHandler;

use App\domain\repositories\Participant\ParticipantRepository;
use App\domain\dispatchers\EventDispatcher;

use App\domain\entities\Participant\Participant;
use App\domain\entities\Participant\Id;
use App\domain\entities\Contest\Id as ContestId;
use App\domain\entities\Participant\Name;
use App\domain\entities\Participant\Phone;
use App\domain\entities\Participant\RegistrationData;
use App\domain\entities\Participant\RegistrationStatus;

class RegisterHandler extends CommandHandler
{
    private $confirmationCodeGenerator;
    private $participants;
    private $dispatcher;

    private $participant;

    public function __construct(ConfirmationCodeGenerator $confirmationCodeGenerator, ParticipantRepository $participants, EventDispatcher $dispatcher)
    {
        $this->confirmationCodeGenerator = $confirmationCodeGenerator;
        $this->participants = $participants;
        $this->dispatcher = $dispatcher;
    }

    public function handle(RegisterCommand $command): void
    {
        $this->initParticipant($command);

        $this->guardPhoneIsUnique();
        $this->persist();
    }

    private function initParticipant(RegisterCommand $command): void
    {
        $this->participant = new Participant(
            Id::next(),
            new ContestId($command->getContestId()),
            new Name(
                $command->getFirstName(),
                $command->getLastName()
            ),
            new Phone($command->getPhone()),
            new RegistrationData(
                new RegistrationStatus(RegistrationStatus::UNCONFIRMED),
                new \DateTimeImmutable(), // registered at
                $this->confirmationCodeGenerator->generate(), // confirmation code
            ),
            $command->getReferralId() ? new Id($command->getContestId()) : null
        );
    }

    private function guardPhoneIsUnique(): void
    {
        if ($this->participants->existsByPhone($this->participant->getPhone())) {
            throw new \DomainException('This phone already registered');
        }
    }

    private function persist(): void
    {
        $this->participants->add($this->participant);
        $this->dispatcher->dispatch($this->participant->releaseEvents());
    }
}
