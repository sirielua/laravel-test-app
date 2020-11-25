<?php

namespace App\domain\service\Participant\LinkFacebook;

use App\domain\repositories\Participant\ParticipantRepository;
use App\domain\dispatchers\EventDispatcher;
use App\domain\entities\Participant\Id;
use App\domain\entities\Participant\FacebookId;

class LinkFacebookHandler
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
    public function handle(LinkFacebookCommand $command): void
    {
        $this->loadParticipant($command);

        $this->chechParticipantIsConfirmed($command->facebookId);
        $this->linkFacebook($command->facebookId);
        $this->persist();
    }

    private function loadParticipant(LinkFacebookCommand $command): void
    {
        $id = new Id($command->id);
        $this->participant = $this->participants->get($id);
    }

    private function chechParticipantIsConfirmed(): void
    {
        if (!$this->participant->getIsRegistrationConfirmed()) {
            throw new exceptions\FacebookCanBeLinkedOnlyToConfirmedParticipantException();
        }
    }

    private function linkFacebook(string $facebookId): void
    {
        $this->participant->attachFacebookId(new FacebookId($facebookId));
    }

    private function persist(): void
    {
        $this->participants->save($this->participant);
        $this->dispatcher->dispatch($this->participant->releaseEvents());
    }
}
