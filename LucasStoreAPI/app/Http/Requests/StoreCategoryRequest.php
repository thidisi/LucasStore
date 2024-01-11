<?php

namespace App\Http\Requests;

use App\Models\Major_Category;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            ],
            'avatar' => [
                'nullable',
                'mimes:png,jpg,jpeg',
                'max:2048',
            ],
            'major_category_id' => [
                'required',
                Rule::exists(Major_Category::class, 'id'),
            ],
        ];
    }
}
