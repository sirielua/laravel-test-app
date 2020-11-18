<?php

namespace App\domain\service\Contest\Activate;

use App\domain\service\CommandHandler;
use App\domain\entities\Cotest\Id;
use App\domain\repositories\Contest\ContestRepository;
use App\domain\dispatchers\EventDispatcher;

class ActivateHandler extends CommandHandler
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
    public function handle(ActivateCommand $command): void
    {
        $contest = $this->contests->get(new Id($command->id));

        $contest->activate();

        $this->contests->save($contest);
        $this->dispatcher->dispatch($contest->releaseEvents());
    }
}
