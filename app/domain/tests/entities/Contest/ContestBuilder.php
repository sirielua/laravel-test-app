<?php

namespace App\domain\tests\entities\Contest;

use App\domain\entities\Contest\Contest;
use App\domain\entities\Contest\Id;
use App\domain\entities\Contest\Status;
use App\domain\entities\Contest\Description;
use App\domain\entities\Contest\Banner;

class ContestBuilder
{
    private $id;
    private $slug;
    private $status;
    private $description;

    public function __construct()
    {
        $this->id = Id::next();
        $this->slug = 'test-contest';
        $this->status = new Status(Status::ACTIVE);
        $this->description = new Description(
            'en',
            'Test Contest Header',
            'Test Contest Subheader',
            'Some explaining text',
            new Banner('/path-to-imaginary-banner/image.jpg')
        );
    }

    public function withId(Id $id): self
    {
        $clone = clone $this;
        $clone->id = $id;
        return $clone;
    }

    public function withSlug(string $slug): self
    {
        $clone = clone $this;
        $clone->slug = $slug;
        return $clone;
    }

    public function withStatus(Status $status): self
    {
        $clone = clone $this;
        $clone->status = $status;
        return $clone;
    }

    public function withDescription(Description $description): self
    {
        $clone = clone $this;
        $clone->description = $description;
        return $clone;
    }

    public function build(): Contest
    {
        $contest = new Contest(
            $this->id,
            $this->slug,
            $this->status,
            $this->description,
        );

        return $contest;
    }
}
