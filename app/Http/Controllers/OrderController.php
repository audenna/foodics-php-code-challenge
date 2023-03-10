<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Repositories\IngredientOrderUsageRepository;
use App\Repositories\IngredientRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductIngredientRepository;
use App\Repositories\ProductRepository;
use App\Services\JsonResponses\JsonResponseAPI;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{

    /**
     *
     * @param OrderRepository $orderRepository
     * @param ProductIngredientRepository $productIngredientRepository
     * @param ProductRepository $productRepository
     * @param IngredientRepository $ingredientRepository
     * @param IngredientOrderUsageRepository $usageRepository
     */
    public function __construct(
        protected OrderRepository                $orderRepository,
        protected ProductIngredientRepository    $productIngredientRepository,
        protected ProductRepository              $productRepository,
        protected IngredientRepository           $ingredientRepository,
        protected IngredientOrderUsageRepository $usageRepository
    ) { }

    /**
     *
     * @param OrderRequest $request
     * @return JsonResponse
     */
    public function processCustomerOrder(OrderRequest $request): JsonResponse
    {
        try {

            $form = $request->validated();
            # process Customer's Order
            $this->orderRepository->processCustomerOrderRequest(
                $form['products'],
                $this->productRepository,
                $this->productIngredientRepository,
                $this->ingredientRepository,
                $this->usageRepository
            );

            return JsonResponseAPI::successResponse("A new Order has been created successfully.", null, 201);

        } catch (\Exception $exception) {
            Log::error($exception);

            return JsonResponseAPI::internalErrorResponse();
        }
    }
}
