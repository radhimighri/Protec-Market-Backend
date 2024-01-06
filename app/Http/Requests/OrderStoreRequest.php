<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
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
            'number' => [
                'required',
                'unique:orders,number',
                'max:255',
                'string',
            ],
            'total_price' => ['nullable', 'numeric'],
            'stauts' => [
                'required',
                'in:paid,processing,packed,picked,cancelled',
            ],
        ];
    }
}
