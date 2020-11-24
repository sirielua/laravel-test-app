<?php

namespace App\domain\tests\service\Contest;

use PHPUnit\Framework\TestCase;

use App\domain\repositories\Contest\MemoryContestRepository;
use App\domain\dispatchers\DummyEventDispatcher;
use App\domain\tests\entities\Contest\ContestBuilder;
use App\domain\service\Contest\Deactivate\DeactivateCommand;
use App\domain\service\Contest\Deactivate\DeactivateHandler;
use App\domain\entities\Contest\Status;

class DeactivateTest extends TestCase
{
    private static $contests;
    private static $dispatcher;

    public static function setUpBeforeClass(): void
    {
        self::$contests = new MemoryContestRepository();
        self::$dispatcher = new DummyEventDispatcher();
    }

    public function testDeactivate()
    {
        $contest = (new ContestBuilder())->withStatus(new Status(Status::ACTIVE))->build();
        self::$contests->add($contest);
        $command = new DeactivateCommand((string)$contest->getId());
        $handler = new DeactivateHandler(self::$contests, self::$dispatcher);

        $handler->handle($command);
        $found = self::$contests->get($contest->getId());

        $this->assertFalse($found->isActive());
    }
}
