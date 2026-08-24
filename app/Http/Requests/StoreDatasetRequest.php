<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDatasetRequest extends FormRequest
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
            'sign_label' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'dataset_need_id' => ['nullable', 'exists:dataset_needs,id'],
            'dataset_file' => ['required', 'file', 'mimes:mp4,webm,png,jpg,jpeg', 'max:51200'],
        ];
    }
}
