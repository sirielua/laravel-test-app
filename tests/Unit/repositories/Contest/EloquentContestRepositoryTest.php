<?php

namespace Tests\Unit\repositories\Contest;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\domain\tests\repositories\Contest\ContestRepositoryTest;
use App\repositories\EloquentContestRepository;
use App\domain\repositories\Hydrator;

class EloquentContestRepositoryTest extends TestCase
{
    use RefreshDatabase, ContestRepositoryTest;

    public static function setUpBeforeClass(): void
    {
        self::$repository = new EloquentContestRepository(new Hydrator());
    }
}
