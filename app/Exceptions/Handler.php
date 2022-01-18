<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Laravel\Passport\Exceptions\OAuthServerException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        OAuthServerException::class,
        Illuminate\Auth\AuthenticationException::class,
        Illuminate\Database\Eloquent\ModelNotFoundException::class,
        Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($request->wantsJson())
            return $this->formatException($request, $exception);

        return parent::render($request, $exception);
    }

    /**
     * Custom exception response
     *   @param  \Illuminate\Http\Request  $request
     *   @param  \Throwable  $exception
     * */
    private function formatException($request, $exception)
    {
        if ($exception instanceof NotFoundHttpException)
            return $this->exceptionResponse('Route not found', 404);

        if ($exception instanceof HttpException) {
            return $this->exceptionResponse(
                $exception->getMessage(),
                $exception->getStatusCode()
            );
        }

        if ($exception instanceof ModelNotFoundException) {
            return $this->exceptionResponse(
                $this->modelMessage($exception),
                404
            );
        }

        if ($exception instanceof AuthorizationException)
            return $this->exceptionResponse($exception->getMessage(), 403);

        // if ($exception instanceof CustomException) {
        //     $code = $exception->getCode();
        //     return response()->json($exception->getFormatted(), $code);
        // }

        return parent::render($request, $exception);
    }

    /**
     * Exception response
     */
    private function exceptionResponse($message, $status)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }

    /**
     * Model not found exception
     * @param ModelNotFoundException $exception
     */
    public function modelMessage(ModelNotFoundException $exception)
    {
        $model = Str::after($exception->getModel(), '\\') ?? 'item';
        $modelId = $exception->getIds()[0] ?? null;

        return sprintf(
            "%s with identifier %s not found",
            strtolower($model),
            $modelId
        );
    }
}
