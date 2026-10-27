<?php

namespace App\Http\Requests;

use App\ProjectStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:150'],
            'client' => ['nullable', 'string', 'max:150'],
            'category' => ['required', 'string', 'max:100'],
            'summary' => ['required', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', Rule::enum(ProjectStatus::class)],
            'progress' => ['required', 'integer', 'between:0,100'],
            'started_on' => ['nullable', 'date'],
            'due_on' => ['nullable', 'date', 'after_or_equal:started_on'],
            'project_url' => ['nullable', 'url:http,https', 'max:255'],
            'tags' => ['nullable', 'string', 'max:500'],
            'theme' => ['required', Rule::in(['violet', 'lime', 'orange'])],
            'is_featured' => ['sometimes', 'boolean'],
            'display_order' => ['required', 'integer', 'between:0,999'],
            'team_member_ids' => ['nullable', 'array'],
            'team_member_ids.*' => ['integer', 'distinct', 'exists:team_members,id'],
        ];
    }
}
