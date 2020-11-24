<?php

namespace App\domain\repositories\Contest;

use App\domain\entities\Contest\Contest;
use App\domain\entities\Contest\Id;
use App\domain\repositories\NotFoundException;
use App\domain\repositories\DuplicateKeyException;

class MemoryContestRepository implements ContestRepository
{
    private $items = [];

    public function get(Id $id): Contest
    {
        if (!isset($this->items[(string)$id])) {
            throw new NotFoundException('Contest not found.');
        }

        return clone $this->items[(string)$id];
    }

    public function add(Contest $contest): void
    {
        if (isset($this->items[(string)$contest->getId()])) {
            throw new DuplicateKeyException('Contest already exists');
        }
        $this->items[(string)$contest->getId()] = clone $contest;
    }

    public function save(Contest $contest): void
    {
        if (!isset($this->items[(string)$contest->getId()])) {
            throw new NotFoundException('Contest not found.');
        }
        $this->items[(string)$contest->getId()] = clone $contest;
    }

    public function remove(Contest $contest): void
    {
        if (isset($this->items[(string)$contest->getId()])) {
            unset($this->items[(string)$contest->getId()]);
        }
    }
}
