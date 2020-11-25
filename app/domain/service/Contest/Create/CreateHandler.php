<?php

namespace App\domain\service\Contest\Create;

use App\domain\repositories\Contest\ContestRepository;
use App\domain\dispatchers\EventDispatcher;

use App\domain\entities\Contest\Contest;
use App\domain\entities\Contest\Id;
use App\domain\entities\Contest\Status;
use App\domain\entities\Contest\Description;
use App\domain\entities\Contest\Banner;

class CreateHandler
{
    private $contests;
    private $dispatcher;

    public function __construct(ContestRepository $contests, EventDispatcher $dispatcher)
    {
        $this->contests = $contests;
        $this->dispatcher = $dispatcher;
    }

    public function handle(CreateCommand $command): Id
    {
        $contest = new Contest(
            Id::next(),
            $command->dto->getIsActive() ? new Status(Status::ACTIVE) : new Status(Status::INACTIVE),
            new Description(
                $command->dto->getHeadline(),
                $command->dto->getSubheadline() ?? null,
                $command->dto->getExplainingText() ?? null,
                $command->dto->getBanner() ? new Banner($command->dto->getBanner()) : null
            ),
        );

        $this->contests->add($contest);
        $this->dispatcher->dispatch($contest->releaseEvents());

        return $contest->getId();
    }
}
