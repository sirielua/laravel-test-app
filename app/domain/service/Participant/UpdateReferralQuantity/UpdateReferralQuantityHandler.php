<?php

namespace App\domain\service\Participant\UpdateReferralQuantity;

use App\domain\repositories\Participant\ParticipantRepository;
use App\domain\dispatchers\EventDispatcher;

use App\domain\entities\Participant\Id;

class UpdateReferralQuantityHandler
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
    public function handle(UpdateReferralQuantityCommand $command): void
    {
        $this->loadParticipant($command);

        $this->updateReferralQuantity();
        $this->persist();
    }

    private function loadParticipant(UpdateReferralQuantityCommand $command): void
    {
        $id = new Id($command->id);
        $this->participant = $this->participants->get($id);
    }

    private function updateReferralQuantity(): void
    {
        $count = $this->participants->getReferralQuantity($this->participant->getId());
        $this->participant->setReferralQuantity($count);
    }

    private function persist(): void
    {
        $this->participants->save($this->participant);
        $this->dispatcher->dispatch($this->participant->releaseEvents());
    }
}
