<?php

namespace App\Http\Controllers;

use App\Services\JsonResponses\JsonResponseAPI;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     *
     * @return JsonResponse
     */
    public function printWelcome(): JsonResponse
    {
        return JsonResponseAPI::successResponse("Welcome to the Foodics API base url.");
    }
}
