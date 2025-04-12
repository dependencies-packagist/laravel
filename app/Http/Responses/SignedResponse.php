<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Jundayw\Render\Facades\Render;
use Symfony\Component\HttpFoundation\Response;

class SignedResponse implements Responsable
{
    public function __construct(
        protected ?string $message = 'OK',
        protected ?string $url = null,
        protected mixed   $with = [],
        protected int     $code = 200,
        protected int     $status = 200,
        protected array   $headers = [],
        protected int     $options = JSON_UNESCAPED_UNICODE
    )
    {
        //
    }

    public function toResponse($request): Response
    {
        if (is_array($this->with)) {
            $this->with = json_encode($this->with, $this->options);
        }
        $this->with = base64_encode($this->with);
        return Render::success($this->message, $this->url, $this->with)
            ->with('code', $this->code)
            ->json($this->status, $this->headers, $this->options)
            ->signed()
            ->response();
    }
}
