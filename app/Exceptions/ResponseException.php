<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Jundayw\Render\Facades\Render;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ResponseException extends RuntimeException
{
    public function __construct(
        public ?string    $error = 'Exception',
        int               $code = -1,
        public ?string    $url = null,
        public mixed      $errors = [],
        public int        $status = 500,
        public array      $headers = [],
        public int        $options = JSON_UNESCAPED_UNICODE,
        public ?Throwable $previous = null
    )
    {
        parent::__construct(message: $error ?? '', code: $code, previous: $previous);
    }

    /**
     * Get the default context variables for logging.
     *
     * @param string|null $key
     * @param mixed|null  $default
     *
     * @return mixed
     */
    public function getContext(string $key = null, mixed $default = null): mixed
    {
        $context = $this->getPrevious() instanceof Throwable ? [
            'message' => $this->getPrevious()->getMessage(),
            'code'    => $this->getPrevious()->getCode(),
            'file'    => $this->getPrevious()->getFile(),
            'line'    => $this->getPrevious()->getLine(),
            'trace'   => $this->getPrevious()->getTraceAsString(),
        ] : [];

        return is_null($key) ? $context : $context[$key] ?? $default;
    }

    /**
     * Get the default response variables for logging.
     *
     * @param string|null $key
     * @param mixed|null  $default
     *
     * @return mixed
     */
    public function getResponse(string $key = null, mixed $default = null): mixed
    {
        $response = [
            'error'   => $this->error,
            'code'    => $this->code,
            'url'     => $this->url,
            'errors'  => $this->errors,
            'status'  => $this->status,
            'headers' => $this->headers,
            'options' => $this->options,
        ];

        return is_null($key) ? $response : $response[$key] ?? $default;
    }

    /**
     * Report the exception.
     *
     * @return bool
     */
    public function report(): bool
    {
        if (is_null($this->getPrevious())) {
            return true;
        }

        return false;
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     *
     * @return Response|bool
     */
    public function render(Request $request): Response|bool
    {
        if (is_null($this->getPrevious())) {
            return Render::error($this->error, $this->url, $this->errors)
                ->with('code', $this->code)
                ->json($this->status, $this->headers, $this->options)
                ->response();
        }

        return false;
    }

    /**
     * Get the actual exception thrown during the stream.
     *
     * @return Throwable
     */
    public function getInnerException(): Throwable
    {
        return $this->getPrevious() ?? $this;
    }
}
