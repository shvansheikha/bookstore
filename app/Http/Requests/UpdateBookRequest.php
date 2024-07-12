<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'min:3'],
            'author_id' => ['integer', 'min:1'],
        ];
    }
}
