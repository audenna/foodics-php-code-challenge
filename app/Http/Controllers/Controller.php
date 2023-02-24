<?php

namespace App\Http\Controllers;

use App\Services\JsonResponses\JsonResponseAPI;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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

    /**
     *
     * @return Factory|View|Application
     */
    public function showEmail(): Factory|View|Application
    {
        return view('welcome');
    }
}
