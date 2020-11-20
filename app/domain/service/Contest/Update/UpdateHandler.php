<?php

namespace App\domain\service\Contest\Update;

use App\domain\repositories\Contest\ContestRepository;
use App\domain\dispatchers\EventDispatcher;
use App\domain\entities\Cotest\Id;
use App\domain\entities\Cotest\Status;

class UpdateHandler
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
    public function handle(UpdateCommand $command): void
    {
        $contest = $this->contests->get(new Id($command->id));

        $contest->changeStatus($command->dto->getIsActive() ? new Status(Status::ACTIVE) : new Status(Status::INACTIVE));
        $contest->changeDescription(new Description(
            $command->dto->getHeadline(),
            $command->dto->getSubheadline() ?? null,
            $command->dto->getExplainingText() ?? null,
            $command->dto->getBanner() ? new Banner($command->dto->getBanner()) : null
        ));

        $this->contests->save($contest);
        $this->dispatcher->dispatch($contest->releaseEvents());
    }
}
