<?php

namespace App\domain\tests\repositories\Contest;

use PHPUnit\Framework\TestCase;
use App\domain\repositories\Contest\MemoryContestRepository;

class MemoryContestRepositoryTest extends TestCase
{
    use ContestRepositoryTest;

    public static function setUpBeforeClass(): void
    {
        self::$repository = new MemoryContestRepository();
    }
}
