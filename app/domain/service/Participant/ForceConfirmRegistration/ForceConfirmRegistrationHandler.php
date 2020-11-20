<?php

namespace App\domain\service\Participant\ForceConfirmRegistration;

use App\domain\repositories\Participant\ParticipantRepository;
use App\domain\dispatchers\EventDispatcher;

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
    public function run(ForceConfirmRegistrationCommand $command): void
    {
        $this->loadParticipant($command);

        $this->confirmRegistration();
        $this->persist();
    }

    private function loadParticipant(ForceConfirmRegistrationCommand $command): void
    {
        $id = new Id($command->id);
        $this->participant = $this->participants->get($id);
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
