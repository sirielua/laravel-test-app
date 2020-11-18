<?php

namespace App\domain\tests\entities\Contest;

use PHPUnit\Framework\TestCase;

use App\domain\entities\Contest\events\ContestSlugChanged;

class ChangeSlugTest extends TestCase
{
    public function testChangeSlug()
    {
        $contest = (new ContestBuilder())->withSlug($oldSlug = 'old-slug')->build();

        $contest->changeSlug($newSlug = 'new-slug');

        $this->assertNotEmpty($events = $contest->releaseEvents());
        $this->assertInstanceOf(ContestSlugChanged::class, end($events));
        $this->assertEquals($newSlug, $contest->getSlug());
    }
}
