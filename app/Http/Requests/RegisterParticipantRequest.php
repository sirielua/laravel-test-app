<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Models\Participant;
use App\Services\Participant\RegistrationService;

class RegisterParticipantRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:20'],
            'last_name' => ['required', 'string', 'max:20'],
            'phone' => ['bail', 'required', 'regex:/\\d+/i', 'string', 'max:20', Rule::unique(Participant::class, 'phone')->ignore($this->getParticipant())],
            'accept_terms' => ['required'],
            'confirm_age' => ['required'],
            'referral_id' => ['nullable', 'string', 'max:255'],
        ];
    }

    private function getParticipant()
    {
        $manager = app()->make(RegistrationService::class);
        return $manager->participant;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
//            'first_name' => '',
//            'last_name' => '',
//            'phone.required' => '',
//            'phone.regex' => '',
//            'accept_terms' => '',
//            'confirm_age' => '',
        ];
    }
}
