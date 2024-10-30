<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SavePasswordRequest extends FormRequest
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
            'otp' => 'required',
            'password' => 'required|confirmed|min:6',
        ];
    }

    public function messages()
    {
        return [
            'otp.required' => 'OTP is required.',
            'password.required' => 'Please enter your Password.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 6 characters long.',
        ];
    }
}
