<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class InitOrderRequest extends FormRequest
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
            'product_id' => 'required|numeric|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ];
    }
}
