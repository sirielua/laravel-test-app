<?php

namespace App\domain\service\Participan\LinkFacebook;

use App\domain\repositories\Participant\ParticipantRepository;
use App\domain\dispatchers\EventDispatcher;

class LinkFacebookHandler implements CommandHandler
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

        $this->linkFacebook($command->facebookId);
        $this->persist();
    }

    private function loadParticipant(LinkFacebookCommand $command): void
    {
        $id = new Id($command->id);
        $this->participant = $this->participants->get($id);
    }

    private function linkFacebook(string $facebookId): void
    {
        $participant->attachFacebookId($facebookId);
    }

    private function persist(): void
    {
        $this->participants->save($this->participant);
        $this->dispatcher->dispatch($this->participant->releaseEvents());
    }
}
