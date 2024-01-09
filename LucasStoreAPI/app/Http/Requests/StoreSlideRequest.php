<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Major_Category;
use App\Models\Slide;

class StoreSlideRequest extends FormRequest
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
            'title' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],
            'slug' => [
                'required',
                'string',
            ],
            'image' => [
                'required',
                'mimes:png,jpg,jpeg',
                'max:2048',
            ],
            'major_category_id' => [
                'required',
                Rule::exists(Major_Category::class, 'id'),
            ],
            'sort_order' => [
                'required',
                Rule::in(Slide::SLIDE_ORDER),
            ],
        ];
    }
}
