<?php

namespace App\domain\tests\service\Contest;

use PHPUnit\Framework\TestCase;

use App\domain\repositories\Contest\MemoryContestRepository;
use App\domain\dispatchers\DummyEventDispatcher;
use App\domain\service\Contest\Create\CreateCommand;
use App\domain\service\Contest\dto\ContestDto;
use App\domain\service\Contest\Create\CreateHandler;

use App\domain\entities\Contest\Id;
use App\domain\entities\Contest\Contest;

class CreateTest extends TestCase
{
    private static $contests;
    private static $dispatcher;

    public static function setUpBeforeClass(): void
    {
        self::$contests = new MemoryContestRepository();
        self::$dispatcher = new DummyEventDispatcher();
    }

    public function testCreate()
    {
        $command = new CreateCommand($dto = new ContestDto(
            $headline = 'Test Contest Header',
            true, // is active
            $subheadline = 'Test Contest Subheader',
            $explainingText = 'Some explaining text',
            $banner = '/path-to-imaginary-banner/image.jpg',
        ));
        $handler = new CreateHandler(self::$contests, self::$dispatcher);

        $id = $handler->handle($command);
        $contest = self::$contests->get($id);

        $this->assertInstanceOf(Id::class, $id);
        $this->assertInstanceOf(Contest::class, $contest);
        $this->assertTrue($contest->isActive());
        $this->assertEquals($headline, $contest->getDescription()->getHeadline());
        $this->assertEquals($subheadline, $contest->getDescription()->getSubheadline());
        $this->assertEquals($explainingText, $contest->getDescription()->getExplainingText());
        $this->assertEquals($banner, $contest->getDescription()->getBanner()->getPath());
    }

    public function testCreateWithMinimalData()
    {
        $command = new CreateCommand($dto = new ContestDto(
            $headline = 'Test Contest Header',
            false, // is active
        ));
        $handler = new CreateHandler(self::$contests, self::$dispatcher);

        $id = $handler->handle($command);
        $contest = self::$contests->get($id);

        $this->assertInstanceOf(Id::class, $id);
        $this->assertInstanceOf(Contest::class, $contest);
        $this->assertFalse($contest->isActive());
        $this->assertEquals($headline, $contest->getDescription()->getHeadline());
        $this->assertNull($contest->getDescription()->getSubheadline());
        $this->assertNull($contest->getDescription()->getExplainingText());
        $this->assertNull($contest->getDescription()->getBanner());
    }
}
