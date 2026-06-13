<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'quantity' => ['required', 'integer', 'min:1'],
        ];

        if ($this->isMethod('post')) {
            $rules['product_id'] = ['required', 'integer', 'exists:products,id'];
        }

        return $rules;
    }
}
