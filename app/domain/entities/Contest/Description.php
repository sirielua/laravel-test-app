<?php

namespace App\domain\entities\Contest;

use Assert\Assertion;

class Description
{
    private $language;
    private $headline;
    private $subheadline;
    private $explainingText;
    private $banner;

    public function __construct(string $language, string $headline, string $subheadline = null, string $explainingText = null, Banner $banner = null)
    {
        Assertion::notEmpty($language);
        Assertion::notEmpty($headline);

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

    public function mergeWith(self $description): void
    {
        $this->language = $description->getLanguage() ?? $this->language;
        $this->headline = $description->getHeadline() ?? $this->headline;
        $this->subheadline = $description->getSubheadline() ?? $this->subheadline;
        $this->explaining_text = $description->getExplainingText() ?? $this->explainingText;
        $this->banner = $description->getBanner() ?? $this->banner;
    }

    public function isEqualTo(self $other): bool
    {
        return ($this->language == $other->language) 
            && ($this->headline == $other->headline)
            && ($this->subheadline == $other->subheadline)
            && ($this->explaining_text == $other->explaining_text)
            && ($this->banner == $other->banner);
    }
}
