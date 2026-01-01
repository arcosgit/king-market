<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'description' => ['required', 'string', 'max:2000'],
            'price' => ['required', 'integer'],
            'characteristics' => ['nullable', 'array', 'max:20'],
            'characteristics.*' => ['array'],
            'characteristics.*.characteristic_key' => ['required', 'string', 'max:255'],
            'characteristics.*.characteristic_value' => ['required', 'string', 'max:255'],
            'categories' => ['required', 'array', 'max:3'],
            'categories.categoryId' => ['required', 'integer', 'exists:categories,id'],
            'categories.subcategoryId' => ['nullable', 'integer', 'exists:subcategories,id'],
            'categories.nestedSubcategoryId' => ['nullable', 'integer', 'exists:nested_categories,id'],
        ];
    }
}
