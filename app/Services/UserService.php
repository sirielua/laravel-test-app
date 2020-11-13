<?php

namespace App\Services;

use App\User;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserService
{
    const STATUS_IS_NOT_ACTIVE = 0;
    const STATUS_IS_ACTIVE = 1;

    public function find($id)
    {
        return User::find($id);
    }

    public function create($data = [])
    {
        $validated = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'is_active' => ['required', Rule::in([
                self::STATUS_IS_NOT_ACTIVE,
                self::STATUS_IS_ACTIVE
            ])],
            'photo_file' => ['nullable', 'mimes:jpeg,jpg,png,gif', 'max:10000'], // max 10000kb
            'photo' => ['nullable'],
        ])->validate();

        $validated['password'] = Hash::make($validated['password']);

        if(isset($validated['photo_file'])) {
            $validated['photo'] = $validated['photo_file']->storeAs(
                'images/avatars',
                $validated['photo_file']->getClientOriginalName(),
                'public'
            );
        }

        if(!$model = User::create($validated)) {
            throw new \DomainException('Creation failed');
        }

        return $model;
    }

    public function update($id, $data = [])
    {
        if(!$model = $this->find($id)) {
            throw new \DomainException('Model not exists!');
        }

        $validated = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($model->id)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'is_active' => ['required', Rule::in([
                self::STATUS_IS_NOT_ACTIVE,
                self::STATUS_IS_ACTIVE
            ])],
            'photo_file' => ['nullable', 'mimes:jpeg,jpg,png,gif', 'max:10000'], // max 10000kb
            'photo' => ['nullable'],
        ])->validate();

        if($validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if(isset($validated['photo_file'])) {
            $validated['photo'] = $validated['photo_file']->storeAs(
                'images/avatars',
                $validated['photo_file']->getClientOriginalName(),
                'public'
            );
        }

        if($validated['photo'] !== $model->photo) {
            Storage::disk('public')->delete($model->photo);
        }

        if(!$model->fill($validated)->update()) {
            throw new \DomainException('Update failed');
        }

        return true;
    }

    public function delete($id)
    {
        if(!$model = $this->find($id)) {
            throw new \DomainException('Model not exists!');
        }

        if($model->id === Auth::user()->id) {
            throw new \DomainException('Suicide attempt!');
        }

        if($model->delete() === false) {
            throw new \DomainException('Deletion failed');
        }

        return true;
    }

    public function activate($id)
    {
        if(!$model = $this->find($id)) {
            throw new \DomainException('Model not exists!');
        }

        $model->is_active = self::STATUS_IS_ACTIVE;

        if(!$model->update()) {
            throw new \DomainException('Update failed');
        }

        return true;
    }

    public function deactivate($id)
    {
        if(!$model = $this->find($id)) {
            throw new \DomainException('Model not exists!');
        }

        $model->is_active = self::STATUS_IS_NOT_ACTIVE;

        if(!$model->update()) {
            throw new \DomainException('Update failed');
        }

        return true;
    }
}
