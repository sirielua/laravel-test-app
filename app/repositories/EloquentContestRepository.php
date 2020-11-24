<?php

namespace App\repositories;

use App\domain\repositories\Contest\ContestRepository;

use App\domain\entities\Contest\Contest;
use App\domain\entities\Contest\Id;
use App\domain\entities\Contest\Status;
use App\domain\entities\Contest\Description;
use App\domain\entities\Contest\Banner;
use App\domain\repositories\NotFoundException;
use App\domain\repositories\DuplicateKeyException;

use App\Models\Contest as Model;
use App\domain\repositories\Hydrator;

class EloquentContestRepository implements ContestRepository
{
    private $hydrator;

    public function __construct(Hydrator $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    public function get(Id $id): Contest
    {
        if (!$model = Model::find((string)$id)) {
            throw new NotFoundException('Contest not found.');
        }

        $contest = $this->hydrator->hydrate(Contest::class, [
            'id' => new Id($model->id),
            'status' => new Status($model->is_active),
            'description' => new Description(
                $model->headline,
                $model->subheadline ?? null,
                $model->explaining_text ?? null,
                $model->banner ? new Banner($model->banner) : null,
            ),
        ]);

        return $contest;
    }

    public function add(Contest $contest): void
    {
        if ($model = Model::find((string)$contest->getId())) {
            throw new DuplicateKeyException('Contest already exists');
        }

        $model = new Model();
        $this->persist($contest, $model);
    }

    private function persist(Contest $contest, Model $model)
    {
        $description = $contest->getDescription();
        $banner = $description->getBanner();

        if (!$model->exists) {
            $model->id = (string)$contest->getId();
        }

        $model->is_active = $contest->isActive();
        $model->headline = $description->getHeadline();
        $model->subheadline = $description->getSubheadline();
        $model->explaining_text = $description->getExplainingText();
        $model->banner = $banner ? $banner->getPath() : null;

        $model->save();
    }

    public function save(Contest $contest): void
    {
        if (!$model = Model::find((string)$contest->getId())) {
            throw new NotFoundException('Contest not found.');
        }

        $this->persist($contest, $model);
    }

    public function remove(Contest $contest): void
    {
        if (!$model = Model::find((string)$contest->getId())) {
            throw new NotFoundException('Contest not found.');
        }

        $model->delete();
    }
}
