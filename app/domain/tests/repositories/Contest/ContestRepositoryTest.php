<?php

namespace App\domain\tests\repositories\Contest;

use App\domain\tests\entities\Contest\ContestBuilder;
use App\domain\entities\Contest\Contest;
use App\domain\entities\Contest\Id;
use App\domain\entities\Contest\Description;
use App\domain\entities\Contest\Banner;
use App\domain\repositories\NotFoundException;
use App\domain\repositories\DuplicateKeyException;

trait ContestRepositoryTest
{
    protected static $repository;

    public function testAdd(): void
    {
        $contest = (new ContestBuilder())->build();

        self::$repository->add($contest);
        $found = self::$repository->get($contest->getId());

        $this->guardEquals($contest, $found);
    }

    private function guardEquals(Contest $created, Contest $fetched): void
    {
        $this->assertEquals($fetched->getId(), $created->getId());
        $this->assertEquals($fetched->getStatus(), $created->getStatus());
        $this->assertEquals($fetched->getDescription(), $created->getDescription());
    }

    public function testAddDuplicatedId(): void
    {
        $this->expectException(DuplicateKeyException::class);

        $contest = (new ContestBuilder())
            ->withId(Id::next())
            ->build();

        self::$repository->add($contest);
        self::$repository->add($contest);
    }

    public function testSave(): void
    {
        $contest = (new ContestBuilder())->build();
        self::$repository->add($contest);

        $contest->deactivate();
        $contest->changeDescription(new Description(
            'New Title',
            'New Subtitle',
            'New Explanation',
            new Banner('/new-banner-path/image.jpg')
        ));

        self::$repository->save($contest);
        $found = self::$repository->get($contest->getId());

        $this->guardEquals($contest, $found);
    }

    public function testGet(): void
    {
        $contest = (new ContestBuilder())->build();
        self::$repository->add($contest);

        $found = self::$repository->get($contest->getId());

        $this->assertEquals($contest->getId(), $found->getId());
    }

    public function testGetNotFound(): void
    {
        $this->expectException(NotFoundException::class);

        self::$repository->get(Id::next());
    }

    public function testRemove(): void
    {
        $this->expectException(NotFoundException::class);
        $contest = (new ContestBuilder())->build();
        self::$repository->add($contest);

        self::$repository->remove($contest);
        self::$repository->get($contest->getId());
    }
}
