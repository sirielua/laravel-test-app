<?php

namespace App\domain\service\Participant\SendConfirmation;

use App\domain\components\RegistrationNotifier\RegistrationNotifier;
use App\domain\repositories\Participant\ParticipantRepository;
use App\domain\dispatchers\EventDispatcher;
use App\domain\entities\Participant\Id;

class SendConfirmationHandler
{
    private $notifier;
    private $participants;
    private $dispatcher;
    private $attemptsAllowed;

    private $participant;

    public function __construct(
        RegistrationNotifier $notifier,
        ParticipantRepository $participants,
        EventDispatcher $dispatcher,
        $attemptsAllowed = 2
    ) {
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

        $this->checkParticipantIsNotConfirmed();
        $this->checkAttempts();
        $this->notifyAboutRegistration();
        $this->persist();
    }

    private function initParticipant(SendConfirmationCommand $command): void
    {
        $id = new Id($command->getId());
        $this->participant = $this->participants->get($id);
    }

    private function checkParticipantIsNotConfirmed()
    {
        if ($this->participant->getIsRegistrationConfirmed()) {
            throw new exceptions\OnlyUnconfirmedParticipantsCouldBeNotifiedException('Registration already confirmed');
        }
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
        $this->notifier->notify($this->participant);
        $this->participant->sendRegistrationConfirmationMessage();
    }

    private function persist(): void
    {
        $this->participants->save($this->participant);
        $this->dispatcher->dispatch($this->participant->releaseEvents());
    }
}
