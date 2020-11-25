<?php

namespace App\domain\tests\service\Participant;

use PHPUnit\Framework\TestCase;

use App\domain\service\Participant\LinkFacebook\LinkFacebookCommand;
use App\domain\service\Participant\LinkFacebook\LinkFacebookHandler;

use App\domain\repositories\Participant\MemoryParticipantRepository;
use App\domain\dispatchers\DummyEventDispatcher;
use App\domain\tests\entities\Participant\ParticipantBuilder;
use App\domain\entities\Participant\Id;
use App\domain\entities\Participant\FacebookId;
use App\domain\repositories\NotFoundException;
use App\domain\service\Participant\LinkFacebook\exceptions\FacebookCanBeLinkedOnlyToConfirmedParticipantException;

class LinkFacebookTest extends TestCase
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
        $participant = (new ParticipantBuilder())
            ->withId($id = Id::next())
            ->build();
        self::$participants->add($participant);

        $command = new LinkFacebookCommand((string)$id, $facebookId = 'facebook-id');
        $handler = new LinkFacebookHandler(self::$participants, self::$dispatcher);

        $handler->handle($command);
        $found = self::$participants->get($participant->getId());

        $this->assertInstanceOf(FacebookId::class, $found->getFacebookId());
        $this->assertEquals($facebookId, (string)$found->getFacebookId());
    }

    public function testLinkToNotExistingParticipant()
    {
        $this->expectException(NotFoundException::class);

        $command = new LinkFacebookCommand(uniqid(), $facebookId = 'facebook-id');
        $handler = new LinkFacebookHandler(self::$participants, self::$dispatcher);

        $handler->handle($command);
    }

    public function testLinkToUnconfirmedParticipant()
    {
        $this->expectException(FacebookCanBeLinkedOnlyToConfirmedParticipantException::class);

        $participant = (new ParticipantBuilder())
            ->withId($id = Id::next())
            ->unconfirmed()
            ->build();
        self::$participants->add($participant);

        $command = new LinkFacebookCommand((string)$id, $facebookId = 'facebook-id');
        $handler = new LinkFacebookHandler(self::$participants, self::$dispatcher);

        $handler->handle($command);
    }
}
