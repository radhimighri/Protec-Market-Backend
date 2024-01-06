<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'picture' => ['image', 'max:1024', 'required'],
            'name' => ['required', 'max:255', 'string'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'weight' => ['required', 'numeric'],
        ];
    }
}
