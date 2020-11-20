<?php

namespace App\domain\service\Participant\Remove;

use App\domain\repositories\Participant\ParticipantRepository;
use App\domain\dispatchers\EventDispatcher;

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
    public function run(RemoveCommand $command): void
    {
        $participant = $this->participants->get($command->id);

        $participant->remove();

        $this->participants->remove($participant);
        $this->dispatcher->dispatch($participant->releaseEvents());
    }
}
