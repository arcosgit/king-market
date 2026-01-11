<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class FindFilterProductRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'price_from' => ['nullable', 'integer'],
            'price_to' => ['nullable', 'integer'],
            'rating' => ['nullable', 'min:1', 'max:5'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $from = $validator->getData()['price_from'] ?? null;
            $to = $validator->getData()['price_to'] ?? null;
            if ($from != null && $to != null && $from > $to) {
                $validator->errors()->add('price_from', __('product.price_from'));
            }
        });
    }
}
