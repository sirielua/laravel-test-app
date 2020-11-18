<?php

namespace App\domain\service\Contest\Create;

use App\domain\service\CommandHandler;
use App\domain\repositories\Contest\ContestRepository;
use App\domain\dispatchers\EventDispatcher;
use App\domain\service\Contest\helpers\ContestDtoHelper;

use App\domain\entities\Cotest\Contest;
use App\domain\entities\Cotest\Id;
use App\domain\entities\Cotest\Status;

class CreateHandler extends CommandHandler
{
    private $contests;
    private $dispatcher;

    public function __construct(ContestRepository $contests, EventDispatcher $dispatcher)
    {
        $this->contests = $contests;
        $this->dispatcher = $dispatcher;
    }

    public function handle(CreateCommand $command): void
    {
        $contest = new Contest(
            Id::next(),
            $command->dto->getIsActive() ? new Status(Status::ACTIVE) : new Status(Status::INACTIVE),
            ContestDtoHelper::dto2Description($command->dto->getDescription()),
        );

        $this->contests->add($contest);
        $this->dispatcher->dispatch($contest->releaseEvents());
    }
}
