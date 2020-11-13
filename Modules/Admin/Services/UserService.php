<?php

namespace Modules\Admin\Services;

use App\Services\UserService as BaseUserService;

use App\User;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

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

        $method = 'batch'.ucfirst($validated['action']);
        if(method_exists($this, $method)) {
            call_user_func([$this, $method], $validated['selected']);
        }
    }

    public function batchDelete($selected = [])
    {
        $models = User::find($selected);
        foreach ($models as $model) {
            if($model->id === Auth::user()->id) {
                // Suicide attempt prevented
                continue;
            }
            $model->delete();
        }
    }
}
