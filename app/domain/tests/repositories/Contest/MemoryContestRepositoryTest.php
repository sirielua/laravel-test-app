<?php

namespace App\domain\tests\repositories\Contest;

use App\domain\repositories\Contest\MemoryContestRepository;

class MemoryContestRepositoryTest extends BaseContestRepositoryTest
{
    public static function setUpBeforeClass(): void
    {
        self::$repository = new MemoryContestRepository();
    }
}
