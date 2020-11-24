<?php

namespace App\Services\User;

use App\Models\User;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserHelper
{
    public function filterDataIfValid($data = [], User $model = null)
    {
        $validated = $this->validate($data, $model);
        return $this->filter($validated, $model);
    }

    public function validate($data = [], User $model = null)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', !$model ? 'unique:users' : Rule::unique('users')->ignore($model->id)],
            'password' => [!$model ? 'required' : 'nullable', 'string', 'min:6', 'confirmed'],
            'is_active' => ['required', Rule::in([
                User::STATUS_IS_NOT_ACTIVE,
                User::STATUS_IS_ACTIVE
            ])],
            'photo_file' => ['nullable', 'mimes:jpeg,jpg,png,gif', 'max:10000'], // max 10000kb
            'photo' => ['nullable'],
        ];

        return Validator::make($data, $rules)->validate();
    }

    private function filter($data = [], User $model = null)
    {
        $this->filterPassword($data);
        User::storeFiles($data, $model);

        return $data;
    }

    private function filterPassword(&$data)
    {
        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
    }
}
