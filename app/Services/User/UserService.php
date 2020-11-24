<?php

namespace App\Services\User;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

class UserService
{
    protected $helper;

    public function __construct(UserHelper $helper)
    {
        $this->helper = $helper;
    }

    public function find($id)
    {
        return User::find($id);
    }

    public function create($data = [])
    {
        $validated = $this->filterData($data);

        if (!$model = User::create($validated)) {
            throw new \DomainException('Creation failed');
        }

        return $model;
    }

    protected function filterData($data, $model = null)
    {
        return $this->helper->filterDataIfValid($data, $model);
    }

    public function update($id, $data = [])
    {
        if (!$model = $this->find($id)) {
            throw new \DomainException('Model not exists!');
        }

        $validated = $this->filterData($data, $model);

        if (!$model->fill($validated)->update()) {
            throw new \DomainException('Update failed');
        }

        return true;
    }

    public function delete($id)
    {
        if (!$model = $this->find($id)) {
            throw new \DomainException('Model not exists!');
        }

        if ($model->id === Auth::user()->id) {
            throw new \DomainException('Suicide attempt!');
        }

        if ($model->delete() === false) {
            throw new \DomainException('Deletion failed');
        }

        return true;
    }

    public function activate($id)
    {
        if (!$model = $this->find($id)) {
            throw new \DomainException('Model not exists!');
        }

        $model->is_active = User::STATUS_IS_ACTIVE;

        if (!$model->update()) {
            throw new \DomainException('Update failed');
        }

        return true;
    }

    public function deactivate($id)
    {
        if (!$model = $this->find($id)) {
            throw new \DomainException('Model not exists!');
        }

        $model->is_active = User::STATUS_IS_NOT_ACTIVE;

        if (!$model->update()) {
            throw new \DomainException('Update failed');
        }

        return true;
    }
}
