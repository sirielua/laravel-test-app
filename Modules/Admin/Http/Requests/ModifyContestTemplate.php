<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Contest as ContestModel;
use App\domain\entities\Contest\Status;
use App\domain\service\Contest\dto\ContestDto;

class ModifyContestTemplate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'headline' => ['required', 'string', 'max:255'],
            'subheadline' => ['nullable', 'string', 'max:255'],
            'explaining_text' => ['nullable', 'string'],
            'is_active' => ['required', Rule::in([
                Status::ACTIVE,
                Status::INACTIVE
            ])],
            'banner_file' => ['nullable', 'mimes:jpeg,jpg,png,gif', 'max:10000'], // max 10000kb
            'banner' => ['nullable'],
        ];
    }

    public function getDto()
    {
        $validated = $this->validated();
        ContestModel::storeFiles($validated, $this->getModelOrNull());

        return new ContestDto(
            $validated['headline'],
            $validated['is_active'],
            $validated['subheadline'],
            $validated['explaining_text'],
            $validated['banner'],
        );
    }

    private function getModelOrNull()
    {
        if ($id = $this->route('contest_template')) {
            return ContestModel::find($id);
        }
    }
}
