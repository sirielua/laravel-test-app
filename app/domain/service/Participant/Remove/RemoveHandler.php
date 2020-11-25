<?php

namespace App\domain\service\Participant\Remove;

use App\domain\repositories\Participant\ParticipantRepository;
use App\domain\dispatchers\EventDispatcher;
use App\domain\entities\Participant\Id;

class RemoveHandler
{
    private $participants;
    private $dispatcher;

    public function __construct(ParticipantRepository $participants, EventDispatcher $dispatcher)
    {
        $this->participants = $participants;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @throws \app\repositories\NotFoundExceptionNotFoundException
     */
    public function handle(RemoveCommand $command): void
    {
        $participant = $this->participants->get(new Id($command->id));

        $participant->remove();

        $this->participants->remove($participant);
        $this->dispatcher->dispatch($participant->releaseEvents());
    }
}
