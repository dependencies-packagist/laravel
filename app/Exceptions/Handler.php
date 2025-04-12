<?php

namespace App\Exceptions;

use App\Http\Responses\ErrorResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
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
            // dump(['reportable', $e, (new \ReflectionClass($e))->isInternal()]);
        });
        $this->renderable(function (Throwable $e, Request $request) {
            // dump(['renderable', $e, (new \ReflectionClass($e))->isInternal()]);
            return (new ErrorResponse(error: $e->getMessage(), code: $e->getCode()))->toResponse($request);
        });
    }

    /**
     * Report or log an exception.
     *
     * @param Throwable $e
     *
     * @return void
     *
     * @throws Throwable
     */
    public function report(Throwable $e): void
    {
        // dump([__FUNCTION__, $e]);
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request   $request
     * @param Throwable $e
     *
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response
    {
        // dump([__FUNCTION__, $e]);
        return parent::render($request, $e);
    }
}
