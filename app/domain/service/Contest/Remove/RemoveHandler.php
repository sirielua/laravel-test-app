<?php

namespace App\domain\service\Contest\Remove;

use App\domain\entities\Cotest\Id;
use App\domain\repositories\Contest\ContestRepository;
use App\domain\dispatchers\EventDispatcher;

class RemoveHandler extends CommandHandler
{
    private $contests;
    private $dispatcher;

    public function __construct(ContestRepository $contests, EventDispatcher $dispatcher)
    {
        $this->contests = $contests;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @throws \app\repositories\NotFoundException
     */
    public function handle(RemoveCommand $command): void
    {
        $contest = $this->contests->get(new Id($command->id));

        $contest->remove();

        $this->contests->remove($contest);
        $this->dispatcher->dispatch($contest->releaseEvents());
    }
}
