<?php

namespace App\domain\service\Contest\dto;

class ContestDto
{
    private $isActive;
    private $description;

    public function __construct(bool $isActive, ContestDescriptionDto $description)
    {
        $this->isActive = $isActive;
        $this->description = $description;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function getDescription(): bool
    {
        return $this->description;
    }
}
