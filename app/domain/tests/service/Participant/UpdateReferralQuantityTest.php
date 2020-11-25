<?php

namespace App\domain\tests\service\Participant;

use PHPUnit\Framework\TestCase;

//use App\domain\service\Participant\UpdateReferralAmount\UpdateReferralAmountCommand;
use App\domain\service\Participant\UpdateReferralQuantity\UpdateReferralQuantityCommand;
//use App\domain\service\Participant\UpdateReferralAmount\UpdateReferralAmountHandler;
use App\domain\service\Participant\UpdateReferralQuantity\UpdateReferralQuantityHandler;

use App\domain\repositories\Participant\MemoryParticipantRepository;
use App\domain\dispatchers\DummyEventDispatcher;
use App\domain\tests\entities\Participant\ParticipantBuilder;
use App\domain\entities\Participant\Id;

class UpdateReferralQuantityTest extends TestCase
{
    private static $participants;
    private static $dispatcher;

    public static function setUpBeforeClass(): void
    {
        self::$participants = new MemoryParticipantRepository();
        self::$dispatcher = new DummyEventDispatcher();
    }

    public function testUpdateReferralQuantity()
    {
        $participant = (new ParticipantBuilder())
            ->withId($id = Id::next())
            ->build();
        $referral = (new ParticipantBuilder())
            ->withReferralId($id)
            ->build();
        self::$participants->add($participant);
        self::$participants->add($referral);

        $command = new UpdateReferralQuantityCommand((string)$id);
        $handler = new UpdateReferralQuantityHandler(self::$participants, self::$dispatcher);

        $handler->handle($command);
        $found = self::$participants->get($id);

        $this->assertEquals(1, $found->getReferralQuantity());
    }

    public function testUnconfirmedReferralsDoesNotQuantity()
    {
        $participant = (new ParticipantBuilder())
            ->withId($id = Id::next())
            ->build();
        $referral = (new ParticipantBuilder())
            ->unconfirmed()
            ->withReferralId($id)
            ->build();
        self::$participants->add($participant);
        self::$participants->add($referral);

        $command = new UpdateReferralQuantityCommand((string)$id);
        $handler = new UpdateReferralQuantityHandler(self::$participants, self::$dispatcher);

        $handler->handle($command);
        $found = self::$participants->get($id);

        $this->assertEquals(0, $found->getReferralQuantity());
    }
}
