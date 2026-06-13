<?php

namespace App\Http\Requests;

use App\Rules\MaxWordsRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductCreationRequest extends FormRequest
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
            // Basic Information
            'name' => 'required|string|max:255',
            'description' => [
             'nullable',
             new MaxWordsRule(500),
             ],
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'required|exists:units,id',
            'tags' => 'nullable|string|max:500',
 
             // Stock Information
             'stock_level' => 'nullable|integer|min:0',
             'min_order_quantity' => 'nullable|integer|min:1',
             'max_order_quantity' => 'nullable|integer|min:1|gte:min_order_quantity',
             'weight' => 'nullable|numeric|min:0',
             'length' => 'nullable|numeric|min:0',
             'width' => 'nullable|numeric|min:0',
             'height' => 'nullable|numeric|min:0',
             'product_color_id' => 'nullable|exists:product_colors,id',
 
               // Images
               'featured_image' => 'required|string', // Assuming Uppy returns file path or URL
              
               // Settings
               'is_online' => 'boolean',
               'is_featured' => 'boolean',
               'is_new' => 'boolean',
            
            ];
    }
    public function getProductData(string $sku = null): array
    {
        $validated = $this->validated();
        
        return [
            // Basic Information
            'name' => $validated['name'],
            'sku' => $sku,
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'] ?? null,
            'unit_id' => $validated['unit_id'],
            'tags' => $validated['tags'] ?? null,
            
            // Stock Information
            'stock_level' => $validated['stock_level'] ?? 0,
            'min_order_quantity' => $validated['min_order_quantity'] ?? 1,
            'max_order_quantity' => $validated['max_order_quantity'] ?? null,
            'weight' => $validated['weight'] ?? null,
            'length' => $validated['length'] ?? null,
            'width' => $validated['width'] ?? null,
            'height' => $validated['height'] ?? null,
            'product_color_id' => $validated['product_color_id'] ?? null,
            
            // Settings
            'is_active' => true,
            'is_online' => $this->boolean('is_online', true),
            'is_featured' => $this->boolean('is_featured', false),
            'is_new' => $this->boolean('is_new', false),
            'created_by' => auth()->id(),
        ];
    }


    public function messages()
    {
        return  [
          'name.required' => 'Please enter a Product name',
            'name.unique' => 'This product already exists',
            'barcode.unique'       => 'This barcode is already registered',
            'barcode.string'       => 'Barcode must be a string',
            'barcode.max'          => 'Barcode must not exceed 50 characters',
            'unit_id.required' => 'Please select a unit',
            'unit_id.exists' => 'Selected unit is invalid',
            'product_color_id.exists' => 'Selected Color is invalid',
            'category_id.required' => 'Please select a category',
            'category_id.exists' => 'Selected category is invalid',
            'stock_level.required' => 'Please enter stock level',
            'stock_level.numeric' => 'Stock level must be a number',
            'stock_level.min' => 'Stock level cannot be negative',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422));
    }
}
