<?php

namespace App\Http\Middleware;


use App\Services\JsonResponses\JsonResponseAPI;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleOrderHeaderValidation
{

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        # handle validation for Content-Type
        if (! $request->hasHeader('Content-Type') || $request->header('Content-Type') !== 'application/json')
            return JsonResponseAPI::errorResponse( 'Include Content-Type and set the value to: application/json in your header.', Response::HTTP_BAD_REQUEST, 'Header Error');

        # handle validation for Accept
        if (! $request->hasHeader('Accept') || $request->header('Accept') !== 'application/json')
            return JsonResponseAPI::errorResponse( 'Include Accept and set the value to: application/json in your header.', Response::HTTP_BAD_REQUEST, 'Header Error');

        return $next($request);
    }
}
