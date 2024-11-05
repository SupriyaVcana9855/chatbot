<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone_number' => 'required|max:15|unique:users,phone_number',
            'password' => 'required|string|min:8',
        ];
    }
    
    public function messages()
    {
        return [
            'name.required' => 'Please enter your name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'The email address format is not valid.',
            'email.unique' => 'An account with this email already exists.',
            'phone_number' => 'Please enter your phone number.',
            'phone_number.unique' => 'This phone number is already associated with another account.',
            'password.required' => 'Please enter your password.',
            'password.min' => 'Your password must be at least :min characters long.',
        ];
    }
}
