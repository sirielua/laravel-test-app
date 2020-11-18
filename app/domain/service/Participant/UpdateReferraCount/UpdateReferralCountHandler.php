<?php

namespace App\domain\service\Participant\UpdateReferralCount;

use App\domain\service\CommandHandler;
use App\domain\repositories\Participant\ParticipantRepository;
use App\domain\dispatchers\EventDispatcher;

class UpdateReferralCountHandler extends CommandHandler
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
    public function handle(UpdateReferralCountCommand $command): void
    {
        $this->loadParticipant($command);

        $this->updateReferralCount();
        $this->persist();
    }

    private function loadParticipant(UpdateReferralCountCommand $command): void
    {
        $id = new Id($command->id);
        $this->participant = $this->participants->get($id);
    }

    private function updateReferralCount(): void
    {
        $count = $this->participants->getReferralCount($this->participant->getId());
        $this->participant->setReferralCount($count);
    }

    private function persist(): void
    {
        $this->participants->save($this->participant);
        $this->dispatcher->dispatch($this->participant->releaseEvents());
    }
}
