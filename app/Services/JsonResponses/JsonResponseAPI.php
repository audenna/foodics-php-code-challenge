<?php


namespace App\Services\JsonResponses;


use Illuminate\Http\JsonResponse;

class JsonResponseAPI
{

    /**
     * set the basic response code types
     * @var int
     */
    public static int $SUCCESS                  = 200;
    public static int $CREATED                  = 201;
    public static int $ACCEPTED                 = 202;
    public static int $NO_CONTENT               = 204;
    public static int $BAD_REQUEST              = 400;
    public static int $UNAUTHORIZED             = 401;
    public static int $FORBIDDEN                = 403;
    public static int $NOT_FOUND                = 404;
    public static int $METHOD_NOT_ALLOW         = 405;
    public static int $UNPROCESSABLE_ENTITY     = 422;
    public static int $INTERNAL_SERVER_ERROR    = 500;
    public static int $NOT_IMPLEMENTED          = 501;
    public static int $ACCOUNT_NOT_VERIFIED     = 209;

    /**
     * Returns a successful response without a status code
     *
     * @param string $message
     * @param null $data
     * @param int $statusCode
     * @param string $header
     * @return JsonResponse
     */
    public static function successResponse(string $message = 'Success', $data = null, int $statusCode = 200, string $header = 'Alert'): JsonResponse
    {
        return response()->json(
            [
                'header'  => $header,
                'status'  => true,
                'message' => $message,
                'data'    => $data
            ],
            $statusCode
        );
    }

    /**
     * This returns an error message back to the client without a status code
     *
     * @param string $message
     * @param int $statusCode
     * @param string $header
     * @return JsonResponse
     */
    public static function errorResponse(string $message = 'Not found', int $statusCode = 200, string $header = 'Error'): JsonResponse
    {
        return response()->json(
            [
                'header'  => $header,
                'status'  => false,
                'message' => $message
            ],
            $statusCode
        );
    }

    /**
     *
     * @param string|null $message
     * @param string $header
     * @return JsonResponse
     */
    public static function internalErrorResponse(string $message = null, string $header = 'Server Error'): JsonResponse
    {
        return self::errorResponse(
            $message ?? "Request failed. Try again later.",
            self::$INTERNAL_SERVER_ERROR,
            $header
        );
    }
}
