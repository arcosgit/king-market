<?php

namespace App\Http\Requests\Catalog;

use Illuminate\Foundation\Http\FormRequest;

class CatalogFilterRequest extends FormRequest
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
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'integer', 'exists:subcategories,id'],
            'nested_subcategory_id' => ['nullable', 'integer', 'exists:nested_categories,id'],
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
