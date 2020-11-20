<?php

namespace App\domain\service\Participant\ConfirmRegistration;

use App\domain\repositories\Participant\ParticipantRepository;
use App\domain\dispatchers\EventDispatcher;

class ConfirmRegistrationHandler
{
    private $participants;
    private $dispatcher;
    private $attemptsAllowed;

    private $participant;

    public function __construct(ParticipantRepository $participants, EventDispatcher $dispatcher, $attemptsAllowed = 10)
    {
        $this->participants = $participants;
        $this->dispatcher = $dispatcher;
        $this->attemptsAllowed = $attemptsAllowed;
    }

    /**
     * @throws \app\repositories\NotFoundException
     * @throws exceptions\NoAttemptsLeftException
     * @throws exceptions\InvalidConfirmationCodeException
     */
    public function handle(ConfirmRegistrationCommand $command): void
    {
        $this->loadParticipant($command->getId());

        $this->checkAttempts();
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

    private function checkAttempts(): void
    {
        $registrationData = $this->participant->getRegistrationData();
        if ($registrationData->getConfirmationAttempts() >= $this->attemptsAllowed) {
            throw new exceptions\NoConfirmationAttemptsLeftException();
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
