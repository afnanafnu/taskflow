<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
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
                Rule::in([
                    'active',
                    'completed',
                    'archived',
                ]),
            ],

            'members' => [
                'nullable',
                'array',
            ],

            'members.*' => [
                'integer',
                'exists:users,id',
            ],
        ];
    }
}