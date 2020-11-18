<?php

namespace App\domain\entities\Contest\events;

use App\domain\entities\Contest\Id;

class ContestSlugChanged
{
    private $id;

    public function __construct(Id $id)
    {
        $this->id = $id;
    }

    public function getId(): Id
    {
        return $this->id;
    }
}
