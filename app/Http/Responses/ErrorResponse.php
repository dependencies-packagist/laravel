<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Jundayw\Render\Facades\Render;
use Symfony\Component\HttpFoundation\Response;

class ErrorResponse implements Responsable
{
    public function __construct(
        protected ?string $error = 'Error',
        protected ?string $url = null,
        protected mixed   $errors = [],
        protected int     $code = 500,
        protected int     $status = 500,
        protected array   $headers = [],
        protected int     $options = JSON_UNESCAPED_UNICODE,
    )
    {
        //
    }

    public function toResponse($request): Response
    {
        return Render::error($this->error, $this->url, $this->errors)
            ->with('code', $this->code)
            ->json($this->status, $this->headers, $this->options)
            ->response();
    }
}
