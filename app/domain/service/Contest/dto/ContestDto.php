<?php

namespace App\domain\service\Contest\dto;

class ContestDto
{
    private $headline;
    private $isActive;
    private $subheadline;
    private $explainingText;
    private $banner;

    public function __construct(string $headline, bool $isActive, string $subheadline = null, string $explainingText = null, string $banner = null)
    {
        $this->headline = $headline;
        $this->isActive = $isActive;
        $this->subheadline = $subheadline;
        $this->explainingText = $explainingText;
        $this->banner = $banner;
    }

    public function getHeadline(): string
    {
        return $this->headline;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function getSubheadline()
    {
        return $this->subheadline;
    }

    public function getExplainingText()
    {
        return $this->explainingText;
    }

    public function getBanner()
    {
        return $this->banner;
    }
}
