<?php

namespace App\domain\tests\entities\Contest;

use PHPUnit\Framework\TestCase;

use App\domain\entities\Contest\Description;
use App\domain\entities\Contest\Banner;
use App\domain\entities\Contest\events\ContestDescriptionChanged;

class ChangeDescriptionTest extends TestCase
{
    public function testChangeDescription()
    {
        $oldDescription = new Description('en', 'Old Title', 'Old Subtitle', 'Old Explanation', new Banner('/old-banner-path/image.jpg'));
        $newDescription = new Description('en', 'New Title', 'New Subtitle', 'New Explanation', new Banner('/new-banner-path/image.jpg'));
        $contest = (new ContestBuilder())->withDescription($oldDescription)->build();

        $contest->changeDescription($newDescription);

        $this->assertNotEmpty($events = $contest->releaseEvents());
        $this->assertInstanceOf(ContestDescriptionChanged::class, end($events));
        $this->assertEquals($newDescription, $contest->getDescription());
    }
}
