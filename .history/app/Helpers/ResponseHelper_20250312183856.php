<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    /**
     * Return success response
     *
     * @param mixed $data
     * @param string|null $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function success(mixed $data = null, ?string $message = null, int $statusCode = 200): JsonResponse
    {
        $response = [
            'success' => true,
        ];

        if ($message !== null) {
            $response['message'] = $message;
        }

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return error response
     *
     * @param string $message
     * @param mixed $errors
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function error(string $message, mixed $errors = null, int $statusCode = 400): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return not found response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return self::error($message, null, 404);
    }

    /**
     * Return validation error response
     *
     * @param mixed $errors
     * @param string $message
     * @return JsonResponse
     */
    public static function validationError(mixed $errors, string $message = 'Validation error'): JsonResponse
    {
        return self::error($message, $errors, 422);
    }

    /**
     * Return unauthorized response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return self::error($message, null, 401);
    }

    /**
     * Return forbidden response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return self::error($message, null, 403);
    }

    /**
     * Return server error response
     *
     * @param string $message
     * @param mixed $errors
     * @return JsonResponse
     */
    public static function serverError(string $message = 'Server Error', mixed $errors = null): JsonResponse
    {
        return self::error($message, $errors, 500);
    }

    /**
     * Return created response
     *
     * @param mixed $data
     * @param string $message
     * @return JsonResponse
     */
    public static function created(mixed $data = null, string $message = 'Resource created successfully'): JsonResponse
    {
        return self::success($data, $message, 201);
    }

    /**
     * Return updated response
     *
     * @param mixed $data
     * @param string $message
     * @return JsonResponse
     */
    public static function updated(mixed $data = null, string $message = 'Resource updated successfully'): JsonResponse
    {
        return self::success($data, $message);
    }

    /**
     * Return deleted response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function deleted(string $message = 'Resource deleted successfully'): JsonResponse
    {
        return self::success(null, $message);
    }

     /**
     * Return unauthorized response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return self::error($message, null, 401);
    }
}