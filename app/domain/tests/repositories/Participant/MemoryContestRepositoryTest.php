<?php

namespace App\domain\tests\repositories\Participant;

use App\domain\repositories\Participant\MemoryParticipantRepository;

class MemoryParticipantRepositoryTest extends BaseParticipantRepositoryTest
{
    public static function setUpBeforeClass(): void
    {
        self::$repository = new MemoryParticipantRepository();
    }
}
