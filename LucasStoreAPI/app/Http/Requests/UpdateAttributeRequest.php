<?php

namespace App\Http\Requests;

use App\Models\Setting;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAttributeRequest extends FormRequest
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
                'max:50',
                Rule::unique('settings')->ignore($this->attribute),
            ],
            'type' => [
                'required',
                Rule::in(Setting::SETTING_TYPE),
            ],
            'parent_id' => [
                'nullable',
                Rule::exists(Setting::class, 'id'),
            ],
        ];
    }
}
