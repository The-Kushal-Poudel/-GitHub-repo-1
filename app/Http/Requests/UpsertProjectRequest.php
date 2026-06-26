<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $projectId = $this->route('project') ? $this->route('project')->id : null;

        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:projects,slug,' . ($projectId ?? 'NULL') . ',id',
            'description' => 'required|string',
            'role' => 'required|string|max:255',
            'tech_stack' => 'required|array',
            'tech_stack.*' => 'string|max:255',
            'features' => 'required|array',
            'features.*' => 'string|max:255',
            'github_link' => 'nullable|url|max:255',
            'live_link' => 'nullable|url|max:255',
            'images' => 'nullable|array',
            'images.*.url' => 'required|string',
            'images.*.alt' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
            'is_visible' => 'required|boolean',
        ];
    }
}
