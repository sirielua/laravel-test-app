<?php

namespace App\domain\repositories\Participant;

use App\domain\entities\Participant\Participant;
use App\domain\entities\Participant\Id;
use App\domain\entities\Participant\Phone;
use App\domain\entities\Contest\Id as ContestId;

use App\domain\repositories\NotFoundException;
use App\domain\repositories\DuplicateKeyException;

class MemoryParticipantRepository implements ParticipantRepository
{
    private $items = [];

    public function get(Id $id): Participant
    {
        if (!isset($this->items[$id->getId()])) {
            throw new NotFoundException('Participant not found.');
        }

        return clone $this->items[$id->getId()];
    }

    public function add(Participant $participant): void
    {
        if (isset($this->items[$participant->getId()->getId()])) {
            throw new DuplicateKeyException('Participant already exists');
        }
        $this->items[$participant->getId()->getId()] = clone $participant;
    }

    public function save(Participant $participant): void
    {
        $this->items[$participant->getId()->getId()] = clone $participant;
    }

    public function remove(Participant $participant): void
    {
        if (isset($this->items[$participant->getId()->getId()])) {
            unset($this->items[$participant->getId()->getId()]);
        }
    }

    public function existsByPhone(Phone $phone): bool
    {
        foreach ($this->items as $participant) {
            if ($participant->getPhone()->isEqualTo($phone)) {
                return true;
            }
        }

        return false;
    }

    public function getReferralCount(Id $id): int
    {
        $count = 0;

        foreach ($this->items as $participant) {
            if ($participant->getReferralId()->isEqualTo($id)) {
                $count++;
            }
        }

        return $ount;
    }
}
