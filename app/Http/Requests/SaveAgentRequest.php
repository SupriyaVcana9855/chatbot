<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;

class SaveAgentRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:ai_agents,email,' . $this->agent_id,
            'phone_number' => 'required|digits_between:10,15|unique:ai_agents,phone_number,' . $this->agent_id,
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.min' => 'The name must be at least 3 characters long.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'This email is already taken.',
            'phone_number.required' => 'The phone number field is required.',
            'phone_number.digits_between' => 'The phone number must be between 10 and 15 digits.',
            'phone_number.unique' => 'This phone number is already taken.',
        ];
    }
}
