<?php

namespace App\domain\service\Participant\SendConfirmation;

use App\domain\service\CommandHandler;

use App\domain\components\ConfirmationCodeGenerator\ConfirmationCodeGenerator;
use App\domain\components\RegistrationNotifier\RegistrationNotifier;
use App\domain\repositories\Participant\ParticipantRepository;
use App\domain\dispatchers\EventDispatcher;

use App\domain\entities\Participant\Id;

class SendConfirmationCommand extends CommandHandler
{
    private $confirmationCodeGenerator;
    private $notifier;
    private $participants;
    private $dispatcher;
    private $attemptsAllowed;

    private $participant;

    public function __construct(
        ConfirmationCodeGenerator $confirmationCodeGenerator,
        RegistrationNotifier $notifier,
        ParticipantRepository $participants,
        EventDispatcher $dispatcher,
        $attemptsAllowed = 2
    ) {
        $this->confirmationCodeGenerator = $confirmationCodeGenerator;
        $this->notifier = $notifier;
        $this->participants = $participants;
        $this->dispatcher = $dispatcher;
        $this->attemptsAllowed = $attemptsAllowed;
    }

    /**
     * @throws \app\repositories\NotFoundExceptionNotFoundException
     * @throws exceptions\CantSendMoreConfirmationsException
     */
    public function handle(SendConfirmationCommand $command): void
    {
        $this->initParticipant($command);

        $this->checkAttempts();
        $this->notifyAboutRegistration();
        $this->persist();
    }

    private function initParticipant(SendConfirmationCommand $command): void
    {
        $this->participant = $this->participants->get(new Id($command->getId()));
    }

    private function checkAttempts(): void
    {
        $registrationData = $this->participant->getRegistrationData();
        if ($registrationData->getConfirmationReceivedTimes() >= $this->attemptsAllowed) {
            throw new exceptions\CantSendMoreConfirmationsException();
        }
    }

    private function notifyAboutRegistration(): void
    {
        $this->participant->setRegistrationConfirmationCode($this->confirmationCodeGenerator->generate());
        $this->notifier->notify($this->participant);
        $this->participant->sendRegistrationConfirmationMessage();
    }

    private function persist(): void
    {
        $this->participants->save($this->participant);
        $this->dispatcher->dispatch($this->participant->releaseEvents());
    }
}
