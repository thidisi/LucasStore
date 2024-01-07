<?php

namespace App\Http\Requests;

use App\Models\Major_Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                Rule::unique('categories')->ignore($this->category),
            ],
            'avatar' => [
                'nullable',
                'mimes:png,jpg,jpeg',
                'max:2048',
            ],
            'status' => ['required', 'in:active,inactive'],
            'major_category_id' => [
                'required',
                Rule::exists(Major_Category::class, 'id'),
            ],
        ];
    }
}
