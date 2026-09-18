<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
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

            'assignee_id' => [
                'sometimes',
                'integer',
                'exists:users,id',
            ],

            'due_from' => [
                'sometimes',
                'date',
            ],

            'due_to' => [
                'sometimes',
                'date',
                'after_or_equal:due_from',
            ],

            'search' => [
                'sometimes',
                'string',
                'max:100',
            ],

            'sort_by' => [
                'sometimes',
                Rule::in([
                    'due_date',
                    'priority',
                    'created_at',
                ]),
            ],

            'sort_direction' => [
                'sometimes',
                Rule::in([
                    'asc',
                    'desc',
                ]),
            ],

            'per_page' => [
                'sometimes',
                'integer',
                'min:1',
                'max:100',
            ],
        ];
    }
}