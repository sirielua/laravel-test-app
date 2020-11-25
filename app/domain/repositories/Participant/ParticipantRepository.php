<?php

namespace App\domain\repositories\Participant;

use App\domain\entities\Participant\Participant;
use App\domain\entities\Participant\Id;
use App\domain\entities\Participant\Phone;
use App\domain\entities\Contest\Id as ContestId;

interface ParticipantRepository
{
    public function get(Id $id): Participant;

    public function add(Participant $participant): void;

    public function save(Participant $participant): void;

    public function remove(Participant $participant): void;

    public function existsByPhone(Phone $phone): bool;

    public function getReferralQuantity(Id $id): int;
}
