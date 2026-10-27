<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:180'],
            'project_type' => ['required', 'in:web-app,website,product-design,other'],
            'message' => ['required', 'string', 'min:20', 'max:3000'],
            'company' => ['nullable', 'string', 'max:120'],
        ];
    }

    public function messages(): array
    {
        return [
            'message.min' => 'Tell us a little more about what you want to build.',
        ];
    }
}
