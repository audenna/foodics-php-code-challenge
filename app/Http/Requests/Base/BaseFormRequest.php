<?php

namespace App\Http\Requests\Base;


use App\Repositories\IngredientRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Services\JsonResponses\JsonResponseAPI;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseFormRequest extends FormRequest
{

    /**
     *
     * @param OrderRepository $orderRepository
     * @param IngredientRepository $ingredientRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(
        protected OrderRepository      $orderRepository,
        protected IngredientRepository $ingredientRepository,
        protected ProductRepository    $productRepository,
    ) { parent::__construct(); }

    /**
     * THis overrides the default throwable failed message in json format
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(JsonResponseAPI::errorResponse($validator->errors()->first(), JsonResponseAPI::$UNPROCESSABLE_ENTITY));
    }
}
