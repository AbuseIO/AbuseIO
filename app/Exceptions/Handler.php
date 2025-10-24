<?php

namespace AbuseIO\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        ValidationException::class,
        ThrottleRequestsException::class,
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable               $e
     *
     * @throws \Throwable
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $e)
    {
        // Handle AJAX requests with JSON responses
        if ($request->expectsJson()) {
            return $this->renderJsonException($request, $e);
        }

        // Handle API routes
        if ($request->is('api/*')) {
            return $this->renderApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Render an exception as JSON.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable               $e
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function renderJsonException($request, Throwable $e)
    {
        $status = $this->getExceptionStatusCode($e);
        $message = $this->getExceptionMessage($e);

        $response = [
            'message' => $message,
            'status'  => $status,
        ];

        if (config('app.debug')) {
            $response['exception'] = get_class($e);
            $response['file'] = $e->getFile();
            $response['line'] = $e->getLine();
            $response['trace'] = collect($e->getTrace())->map(function ($trace) {
                return collect($trace)->except(['args'])->all();
            })->all();
        }

        return response()->json($response, $status);
    }

    /**
     * Render an exception for API routes.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable               $e
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function renderApiException($request, Throwable $e)
    {
        $status = $this->getExceptionStatusCode($e);

        return response()->json([
            'error' => [
                'message'     => $this->getExceptionMessage($e),
                'status_code' => $status,
            ],
        ], $status);
    }

    /**
     * Get the status code from the exception.
     *
     * @param \Throwable $e
     *
     * @return int
     */
    protected function getExceptionStatusCode(Throwable $e): int
    {
        if ($e instanceof HttpException) {
            return $e->getStatusCode();
        }

        if ($e instanceof ModelNotFoundException) {
            return 404;
        }

        if ($e instanceof AuthorizationException) {
            return 403;
        }

        if ($e instanceof AuthenticationException) {
            return 401;
        }

        if ($e instanceof ValidationException) {
            return 422;
        }

        if ($e instanceof TokenMismatchException) {
            return 419;
        }

        if ($e instanceof ThrottleRequestsException) {
            return 429;
        }

        return 500;
    }

    /**
     * Get the exception message.
     *
     * @param \Throwable $e
     *
     * @return string
     */
    protected function getExceptionMessage(Throwable $e): string
    {
        if ($e instanceof ValidationException) {
            return 'The given data was invalid.';
        }

        if ($e instanceof ModelNotFoundException) {
            return 'Resource not found.';
        }

        if ($e instanceof AuthorizationException) {
            return 'This action is unauthorized.';
        }

        if ($e instanceof AuthenticationException) {
            return 'Unauthenticated.';
        }

        if ($e instanceof TokenMismatchException) {
            return 'CSRF token mismatch.';
        }

        if ($e instanceof NotFoundHttpException) {
            return 'The requested resource was not found.';
        }

        if ($e instanceof ThrottleRequestsException) {
            return 'Too many requests.';
        }

        // Don't expose internal errors in production
        if (config('app.debug')) {
            return $e->getMessage();
        }

        return 'Server Error';
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param \Illuminate\Http\Request                 $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        return redirect()->guest(route('login'));
    }

    /**
     * Convert a validation exception into a JSON response.
     *
     * @param \Illuminate\Http\Request                   $request
     * @param \Illuminate\Validation\ValidationException $exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            'message' => $exception->getMessage(),
            'errors'  => $exception->errors(),
        ], $exception->status);
    }
}
