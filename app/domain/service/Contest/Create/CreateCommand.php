<?php

namespace App\domain\service\Contest\Create;

use App\domain\service\Command;
use App\domain\service\Contest\dto\ContestDto;

final class CreateCommand extends Command
{
    private $dto;

    public function __construct(ContestDto $dto)
    {
        $this->dto = $dto;
    }

    public function getDto(): string
    {
        return $this->dto;
    }
}
