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
        if (!isset($this->items[(string)$id])) {
            throw new NotFoundException('Participant not found.');
        }

        return clone $this->items[(string)$id];
    }

    public function add(Participant $participant): void
    {
        if (isset($this->items[(string)$participant->getId()])) {
            throw new DuplicateKeyException('Participant already exists');
        }

        $this->items[(string)$participant->getId()] = clone $participant;
    }


    public function save(Participant $participant): void
    {
        if (!isset($this->items[(string)$participant->getId()])) {
            throw new NotFoundException('Participant not found.');
        }

        $this->items[(string)$participant->getId()] = clone $participant;
    }

    public function remove(Participant $participant): void
    {
        if (isset($this->items[(string)$participant->getId()])) {
            unset($this->items[(string)$participant->getId()]);
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
