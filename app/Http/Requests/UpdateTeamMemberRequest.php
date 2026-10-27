<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeamMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'role' => ['required', 'string', 'max:120'],
            'email' => [
                'nullable',
                'email:rfc',
                'max:180',
                Rule::unique('team_members', 'email')->ignore($this->route('team_member')),
            ],
            'avatar_url' => ['nullable', 'url:http,https', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'skills' => ['nullable', 'string', 'max:500'],
            'is_active' => ['sometimes', 'boolean'],
            'display_order' => ['required', 'integer', 'between:0,999'],
        ];
    }
}
