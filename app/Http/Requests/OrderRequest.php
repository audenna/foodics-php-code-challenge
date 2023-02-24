<?php

namespace App\Http\Requests;

use App\Http\Requests\Base\BaseFormRequest;
use Illuminate\Support\Facades\Log;

class OrderRequest extends BaseFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "products" => "required|array",
            'products.*.product_id' => "required|integer|exists:products,id",
            'products.*.quantity' => [
                'required',
                'integer',
                function ($key, $value, $callback) {
                    # check that at least one Ingredient has enough to handle the request
                    if (! $this->ingredientRepository->countRecords('id', ['is_out_of_stock' => 0])) {
                        return $callback("Unable to proceed with your request at this time. Product ingredients are out of stock.");
                    }
                }
            ]
        ];
    }

    /**
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'products.required'              => 'An array of product is required to proceed.',
            'products.array'                 => 'Products key is not an array.',
            'products.*.product_id.required' => 'You need to select at least, one product to proceed.',
            'products.*.product_id.integer'  => 'At least, one selected product is invalid.',
        ];
    }
}
