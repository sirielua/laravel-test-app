<?php

namespace App\domain\tests\service\Participant;

use PHPUnit\Framework\TestCase;

use App\domain\service\Participant\Remove\RemoveCommand;
use App\domain\service\Participant\Remove\RemoveHandler;

use App\domain\repositories\Participant\MemoryParticipantRepository;
use App\domain\dispatchers\DummyEventDispatcher;
use App\domain\tests\entities\Participant\ParticipantBuilder;
use App\domain\entities\Participant\Id;
use App\domain\repositories\NotFoundException;

class RemoveTest extends TestCase
{
    private static $participants;
    private static $dispatcher;

    public static function setUpBeforeClass(): void
    {
        self::$participants = new MemoryParticipantRepository();
        self::$dispatcher = new DummyEventDispatcher();
    }

    public function testRemove()
    {
        $this->expectException(NotFoundException::class);

        $participant = (new ParticipantBuilder())
            ->withId($id = Id::next())
            ->build();
        self::$participants->add($participant);
        $command = new RemoveCommand((string)$id);
        $handler = new RemoveHandler(self::$participants, self::$dispatcher);

        $handler->handle($command);
        self::$participants->get($participant->getId());
    }

    public function testRemoveNonExistent()
    {
        $this->expectException(NotFoundException::class);

        $command = new RemoveCommand((string)uniqid());
        $handler = new RemoveHandler(self::$participants, self::$dispatcher);

        $handler->handle($command);
    }
}
