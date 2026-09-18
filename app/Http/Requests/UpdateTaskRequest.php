<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'sometimes',
                'required',
                'string',
                'min:2',
                'max:255',
            ],

            'description' => [
                'sometimes',
                'nullable',
                'string',
                'max:5000',
            ],

            'status' => [
                'sometimes',
                'string',
                Rule::in([
                    'todo',
                    'in_progress',
                    'completed',
                    'blocked',
                ]),
            ],

            'priority' => [
                'sometimes',
                'string',
                Rule::in([
                    'low',
                    'medium',
                    'high',
                ]),
            ],

            'due_date' => [
                'sometimes',
                'nullable',
                'date',
            ],

            'assignee_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:users,id',
            ],
        ];
    }
}