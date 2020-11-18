<?php

namespace App\domain\service\Contest\dto;

class ContestDescriptionDto
{
    private $language;
    private $headline;
    private $subheadline;
    private $explainingText;
    private $banner;

    public function __construct(string $language, string $headline, string $subheadline = null, string $explainingText = null, string $banner = null)
    {
        $this->language = $language;
        $this->headline = $headline;
        $this->subheadline = $subheadline;
        $this->explaining_text = $explainingText;
        $this->banner = $banner;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getHeadline(): string
    {
        return $this->headline;
    }

    public function getSubheadline(): string
    {
        return $this->subheadline;
    }

    public function getExplainingText(): string
    {
        return $this->explainingText;
    }

    public function getBanner(): Banner
    {
        return $this->banner;
    }
}
