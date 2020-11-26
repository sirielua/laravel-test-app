<?php

namespace Tests\Unit\repositories\Participant;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\domain\tests\repositories\Participant\ParticipantRepositoryTest;
use App\repositories\EloquentParticipantRepository;
use App\domain\repositories\Hydrator;

class EloquentParticipantRepositoryTest extends TestCase
{
    use RefreshDatabase, ParticipantRepositoryTest;

    public static function setUpBeforeClass(): void
    {
        self::$repository = new EloquentParticipantRepository(new Hydrator());
    }
}
