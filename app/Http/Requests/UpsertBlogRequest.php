<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertBlogRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'link' => 'nullable|url|max:255',
            'published_at' => 'nullable|date',
            'is_published' => 'required|boolean',
            'sort_order' => 'required|integer',
        ];
    }
}
