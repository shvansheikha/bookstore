<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'min:3'],
            'author_id' => ['required', 'integer', 'min:1',
                Rule::exists('authors', 'id')->where(fn($query) => $query->where('user_id', auth()->id()))]];
    }
}
