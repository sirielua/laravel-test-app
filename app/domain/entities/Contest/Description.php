<?php

namespace App\domain\entities\Contest;

use Assert\Assertion;

class Description
{
    private $headline;
    private $subheadline;
    private $explainingText;
    private $banner;

    public function __construct(string $headline, string $subheadline = null, string $explainingText = null, Banner $banner = null)
    {
        Assertion::notEmpty($headline);

        $this->headline = $headline;
        $this->subheadline = $subheadline;
        $this->explainingText = $explainingText;
        $this->banner = $banner;
    }

    public function getHeadline(): string
    {
        return $this->headline;
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

    public function mergeWith(self $description): void
    {
        $this->headline = $description->getHeadline() ?? $this->headline;
        $this->subheadline = $description->getSubheadline() ?? $this->subheadline;
        $this->explainingText = $description->getExplainingText() ?? $this->explainingText;
        $this->banner = $description->getBanner() ?? $this->banner;
    }

    public function isEqualTo(self $other): bool
    {
        return ($this->headline == $other->headline)
            && ($this->subheadline == $other->subheadline)
            && ($this->explainingText == $other->explainingText)
            && ($this->banner == $other->banner);
    }
}
