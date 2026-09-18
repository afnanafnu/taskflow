<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'min:2',
                'max:150',
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'status' => [
                'required',
                'string',
                Rule::in([
                    'todo',
                    'in_progress',
                    'completed',
                    'blocked',
                ]),
            ],

            'priority' => [
                'required',
                'string',
                Rule::in([
                    'low',
                    'medium',
                    'high',
                ]),
            ],

            'assignee_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],
        ];
    }
}