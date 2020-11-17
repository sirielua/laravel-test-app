<?php

namespace Modules\Admin\Services\User;

use App\Services\User\UserService as BaseUserService;

use App\User;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserService extends BaseUserService
{
    protected const BATCH_ACTION_DELETE = 'delete';
    protected const BATCH_ACTION_ACTIVATE = 'activate';
    protected const BATCH_ACTION_DEACTIVATE = 'deactivate';

    public static function batchActions()
    {
        return [
            self::BATCH_ACTION_DELETE,
            self::BATCH_ACTION_ACTIVATE,
            self::BATCH_ACTION_DEACTIVATE,
        ];
    }

    public function batchUpdate($data)
    {
        $validated = Validator::make($data, [
            'action' => ['required', 'string', Rule::in(self::batchActions())],
            'selected' => ['required', 'array'],
            'selected.*' => ['integer'],
        ])->validate();

        $models = User::find($validated['selected']);

        foreach ($models as $model) {
            call_user_func([$this, $validated['action']], $model->id);
        }
    }
}
