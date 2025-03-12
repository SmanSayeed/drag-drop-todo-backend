<?php

namespace App\Exceptions;

use App\Helpers\ResponseHelper;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Convert API exceptions to JSON responses
        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                if ($e instanceof ValidationException) {
                    return ResponseHelper::validationError($e->validator->errors());
                }

                if ($e instanceof AuthenticationException) {
                    return ResponseHelper::unauthorized('Unauthorized');
                }

                if ($e instanceof ModelNotFoundException) {
                    return ResponseHelper::notFound();
                }

                if ($e instanceof NotFoundHttpException) {
                    return ResponseHelper::notFound('Endpoint not found');
                }

                // General server errors
                return ResponseHelper::serverError($e->getMessage());
            }
        });
    }
}