<?php

namespace App\domain\tests\service\Contest;

use PHPUnit\Framework\TestCase;

use App\domain\repositories\Contest\MemoryContestRepository;
use App\domain\dispatchers\DummyEventDispatcher;
use App\domain\tests\entities\Contest\ContestBuilder;
use App\domain\service\Contest\Remove\RemoveCommand;
use App\domain\service\Contest\Remove\RemoveHandler;
use App\domain\repositories\NotFoundException;
use App\domain\entities\Contest\Status;

class RemoveTest extends TestCase
{
    private static $contests;
    private static $dispatcher;

    public static function setUpBeforeClass(): void
    {
        self::$contests = new MemoryContestRepository();
        self::$dispatcher = new DummyEventDispatcher();
    }

    public function testRemove()
    {
        $this->expectException(NotFoundException::class);

        $contest = (new ContestBuilder())->withStatus(new Status(Status::INACTIVE))->build();
        self::$contests->add($contest);
        $command = new RemoveCommand((string)$contest->getId());
        $handler = new RemoveHandler(self::$contests, self::$dispatcher);

        $handler->handle($command);
        self::$contests->get($contest->getId());
    }

    public function testRemoveActive()
    {
        $this->expectException(\DomainException::class);

        $contest = (new ContestBuilder())->build();
        self::$contests->add($contest);
        $command = new RemoveCommand((string)$contest->getId());
        $handler = new RemoveHandler(self::$contests, self::$dispatcher);

        $handler->handle($command);
    }
}
