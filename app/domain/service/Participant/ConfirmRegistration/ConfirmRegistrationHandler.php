<?php

namespace App\domain\service\Participant\ConfirmRegistration;

use App\domain\repositories\Participant\ParticipantRepository;
use App\domain\dispatchers\EventDispatcher;
use App\domain\entities\Participant\Id;

class ConfirmRegistrationHandler
{
    private $participants;
    private $dispatcher;

    private $participant;

    public function __construct(ParticipantRepository $participants, EventDispatcher $dispatcher)
    {
        $this->participants = $participants;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @throws \app\repositories\NotFoundException
     * @throws exceptions\InvalidConfirmationCodeException
     */
    public function handle(ConfirmRegistrationCommand $command): void
    {
        $this->loadParticipant($command->getId());
        $this->checkIsRegistrationIsUnconfirmed();
        $this->registerNewAttempt();

        try {
            $this->validateCode($command->getCode());
            $this->confirmRegistration();
        } catch (\DomainException $e) {
            throw $e;
        } finally {
            $this->persist();
        }
    }

    private function loadParticipant(string $id): void
    {
        $this->participant = $this->participants->get(new Id($id));
    }

    private function checkIsRegistrationIsUnconfirmed(): void
    {
        if ($this->participant->getIsRegistrationConfirmed()) {
            throw new exceptions\RegistrationAlreadyConfirmedException();
        }
    }

    private function registerNewAttempt(): void
    {
        $this->participant->registerConfirmationAttempt();
    }

    private function validateCode($code): void
    {
        $registrationData = $this->participant->getRegistrationData();
        if ($code !== $registrationData->getConfirmationCode()) {
            throw new exceptions\InvalidConfirmationCodeException();
        }
    }

    private function confirmRegistration(): void
    {
        $this->participant->confirmRegistration();
    }

    private function persist(): void
    {
        $this->participants->save($this->participant);
        $this->dispatcher->dispatch($this->participant->releaseEvents());
    }
}
