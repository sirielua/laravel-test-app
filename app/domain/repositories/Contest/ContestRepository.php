<?php

namespace App\domain\repositories\Contest;

use App\domain\entities\Contest\Contest;
use App\domain\entities\Contest\Id;

interface ContestRepository
{
    public function get(Id $id): Contest;

    public function add(Contest $contest): void;

    public function save(Contest $contest): void;

    public function remove(Contest $contest): void;
}
