<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDatasetNeedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'target_count' => ['required', 'integer', 'min:1'],
            'priority' => ['required', 'string', 'in:high,medium,low'],
            'description' => ['nullable', 'string'],
        ];
    }
}
