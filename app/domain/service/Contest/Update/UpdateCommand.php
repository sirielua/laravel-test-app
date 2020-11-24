<?php

namespace App\domain\service\Contest\Update;

use App\domain\service\CommandTrait;
use App\domain\service\Contest\dto\ContestDto;
use Assert\Assertion;

class UpdateCommand
{
    use CommandTrait;

    private $id;
    private $dto;
    private $contests;
    private $dispatcher;

    public function __construct(string $id, ContestDto $dto)
    {
        Assertion::notEmpty($id);

        $this->id = $id;
        $this->dto = $dto;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDto(): ContestDto
    {
        return $this->dto;
    }
}
