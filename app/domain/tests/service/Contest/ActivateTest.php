<?php

namespace App\domain\tests\service\Contest;

use PHPUnit\Framework\TestCase;

use App\domain\repositories\Contest\MemoryContestRepository;
use App\domain\dispatchers\DummyEventDispatcher;
use App\domain\tests\entities\Contest\ContestBuilder;
use App\domain\service\Contest\Activate\ActivateCommand;
use App\domain\service\Contest\Activate\ActivateHandler;
use App\domain\entities\Contest\Status;

class ActivateTest extends TestCase
{
    private static $contests;
    private static $dispatcher;

    public static function setUpBeforeClass(): void
    {
        self::$contests = new MemoryContestRepository();
        self::$dispatcher = new DummyEventDispatcher();
    }

    public function testActivate()
    {
        $contest = (new ContestBuilder())->withStatus(new Status(Status::INACTIVE))->build();
        self::$contests->add($contest);
        $command = new ActivateCommand((string)$contest->getId());
        $handler = new ActivateHandler(self::$contests, self::$dispatcher);

        $handler->handle($command);
        $found = self::$contests->get($contest->getId());

        $this->assertTrue($found->isActive());
    }
}
