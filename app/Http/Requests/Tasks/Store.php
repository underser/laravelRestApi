<?php

namespace App\Http\Requests\Tasks;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Store extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()?->can('manage tasks');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required',
            'description' => 'string',
            'image' => 'file|max:2048|mimes:jpg,png',
            'estimation' => 'numeric',
            'user_id' => [
                'required',
                Rule::exists('users', 'id')
            ],
            'project_id' => [
                'required',
                Rule::exists('projects', 'id')
            ],
            'task_status_id' => [
                'required',
                Rule::exists('task_statuses', 'id')
            ]
        ];
    }
}
