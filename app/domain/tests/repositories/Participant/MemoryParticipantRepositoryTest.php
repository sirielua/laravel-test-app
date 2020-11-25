<?php

namespace App\domain\tests\repositories\Participant;

use PHPUnit\Framework\TestCase;
use App\domain\repositories\Participant\MemoryParticipantRepository;

class MemoryParticipantRepositoryTest extends TestCase
{
    use ParticipantRepositoryTest;

    public static function setUpBeforeClass(): void
    {
        self::$repository = new MemoryParticipantRepository();
    }
}
