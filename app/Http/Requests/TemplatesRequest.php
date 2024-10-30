<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TemplatesRequest extends FormRequest
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
                'name' => 'required',
                'temp_title' => 'required|string|min:10',
                'temp_description' => 'required|string|min:20',
                'font' => 'required',
                'font_size' => 'required',
            ];
        }
        
        public function messages()
        {
            return [
                'name.required' => 'The name field is required.',
                'name.min' => 'The name must be at least 3 characters long.',
                'temp_title.required' => 'The title field is required.',
                'temp_title.min' => 'The title must be at least 10 characters long.',
                'temp_description.required' => 'The description field is required.',
                'temp_description.min' => 'The description must be at least 20 characters long.',
                'font.required' => 'The font field is required.',
                'font_size.required' => 'The font size field is required.',
            ];
        }
    
}
