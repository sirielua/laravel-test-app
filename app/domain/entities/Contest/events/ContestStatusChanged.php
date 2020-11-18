<?php

namespace App\domain\entities\Contest\events;

use App\domain\entities\Contest\Id;
use App\domain\entities\Contest\Status;

class ContestStatusChanged
{
    private $id;

    public function __construct(Id $id, Status $status)
    {
        $this->id = $id;
        $this->status = $status;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }
}
