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
        if (!isset($this->items[$id->getId()])) {
            throw new NotFoundException('Contest not found.');
        }

        return clone $this->items[$id->getId()];
    }

    public function add(Contest $contest): void
    {
        if (isset($this->items[$contest->getId()->getId()])) {
            throw new DuplicateKeyException('Contest already exists');
        }
        $this->items[$contest->getId()->getId()] = clone $contest;
    }

    public function save(Contest $contest): void
    {
        $this->items[$contest->getId()->getId()] = clone $contest;
    }

    public function remove(Contest $contest): void
    {
        if (isset($this->items[$contest->getId()->getId()])) {
            unset($this->items[$contest->getId()->getId()]);
        }
    }
}
