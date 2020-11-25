<?php

namespace App\domain\service\Participant\ForceConfirmRegistration;

use App\domain\repositories\Participant\ParticipantRepository;
use App\domain\dispatchers\EventDispatcher;

use App\domain\entities\Participant\Id;

class ForceConfirmRegistrationHandler
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
     * @throws \app\repositories\NotFoundExceptionNotFoundException
     */
    public function handle(ForceConfirmRegistrationCommand $command): void
    {
        $this->loadParticipant($command);
        $this->confirmRegistrationIfNotConfirmed();
        $this->persist();
    }

    private function loadParticipant(ForceConfirmRegistrationCommand $command): void
    {
        $id = new Id($command->id);
        $this->participant = $this->participants->get($id);
    }

    private function confirmRegistrationIfNotConfirmed(): void
    {
        if (!$this->participant->getIsRegistrationConfirmed()) {
            $this->participant->registerConfirmationAttempt();
            $this->participant->confirmRegistration();
        }
    }

    private function persist(): void
    {
        $this->participants->save($this->participant);
        $this->dispatcher->dispatch($this->participant->releaseEvents());
    }
}
