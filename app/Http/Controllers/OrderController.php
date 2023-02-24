<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Repositories\OrderRepository;
use App\Services\JsonResponses\JsonResponseAPI;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{

    /**
     *
     * @param OrderRepository $orderRepository
     */
    public function __construct(protected OrderRepository $orderRepository) { }

    /**
     *
     * @param OrderRequest $request
     * @return JsonResponse
     */
    public function processCustomerOrder(OrderRequest $request): JsonResponse
    {
        try {

            # process Customer's Order

            return JsonResponseAPI::successResponse();

        } catch (\Exception $exception) {
            Log::error($exception);

            return JsonResponseAPI::internalErrorResponse();
        }
    }
}
