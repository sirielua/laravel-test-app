<?php

namespace App\domain\tests\service\Contest;

use PHPUnit\Framework\TestCase;

use App\domain\repositories\Contest\MemoryContestRepository;
use App\domain\dispatchers\DummyEventDispatcher;
use App\domain\tests\entities\Contest\ContestBuilder;
use App\domain\service\Contest\dto\ContestDto;
use App\domain\service\Contest\Update\UpdateCommand;
use App\domain\service\Contest\Update\UpdateHandler;

use App\domain\entities\Contest\Id;
use App\domain\entities\Contest\Contest;
use App\domain\entities\Contest\Status;

class UpdateTest extends TestCase
{
    private static $contests;
    private static $dispatcher;

    public static function setUpBeforeClass(): void
    {
        self::$contests = new MemoryContestRepository();
        self::$dispatcher = new DummyEventDispatcher();
    }

    public function testUpdate()
    {
        $contest = (new ContestBuilder())
            ->withStatus(new Status(Status::INACTIVE))
            ->build();
        self::$contests->add($contest);
        $command = new UpdateCommand((string)$contest->getId(), new ContestDto(
            $headline = 'New Test Contest Header',
            true, // is active
            $subheadline = 'New Test Contest Subheader',
            $explainingText = 'New Some explaining text',
            $banner = '/path-to-new-imaginary-banner/image.jpg',
        ));
        $handler = new UpdateHandler(self::$contests, self::$dispatcher);

        $handler->handle($command);
        $found = self::$contests->get($contest->getId());

        $this->assertTrue($found->isActive());
        $this->assertEquals($headline, $found->getDescription()->getHeadline());
        $this->assertEquals($subheadline, $found->getDescription()->getSubheadline());
        $this->assertEquals($explainingText, $found->getDescription()->getExplainingText());
        $this->assertEquals($banner, $found->getDescription()->getBanner()->getPath());
    }
}
