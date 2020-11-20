<?php

namespace App\domain\service\Contest\Create;

use App\domain\service\CommandTrait;
use App\domain\service\Contest\dto\ContestDto;

class CreateCommand
{
    use CommandTrait;

    private $dto;

    public function __construct(ContestDto $dto)
    {
        $this->dto = $dto;
    }

    public function getDto(): ContestDto
    {
        return $this->dto;
    }
}
