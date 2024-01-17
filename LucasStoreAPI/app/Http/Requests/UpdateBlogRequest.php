<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
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
                'unique:blogs,title',
                'string',
                'max:150',
                Rule::unique('blogs')->ignore($this->blog),
            ],
            'image' => [
                'nullable',
                'mimes:png,jpg,jpeg',
                'max:2048',
            ],
            'content' => [
                'required',
            ],
        ];
    }
}
