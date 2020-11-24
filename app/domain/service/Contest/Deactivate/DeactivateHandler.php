<?php

namespace App\domain\service\Contest\Deactivate;

use App\domain\entities\Contest\Id;
use App\domain\repositories\Contest\ContestRepository;
use App\domain\dispatchers\EventDispatcher;

class DeactivateHandler
{
    private $contests;
    private $dispatcher;

    public function __construct(ContestRepository $contests, EventDispatcher $dispatcher)
    {
        $this->contests = $contests;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @throws \app\repositories\NotFoundExceptionNotFoundException
     */
    public function handle(DeactivateCommand $command): void
    {
        $contest = $this->contests->get(new Id($command->id));

        $contest->deactivate();

        $this->contests->save($contest);
        $this->dispatcher->dispatch($contest->releaseEvents());
    }
}
