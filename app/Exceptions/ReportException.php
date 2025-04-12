<?php

namespace App\Exceptions;

use Illuminate\Contracts\Debug\ExceptionHandler;
use ReflectionClass;
use Throwable;

class ReportException
{
    public function __construct(
        public ?string    $error = 'Exception',
        protected int     $code = -1,
        public ?string    $url = null,
        public mixed      $errors = [],
        public int        $status = 500,
        public array      $headers = [],
        public int        $options = JSON_UNESCAPED_UNICODE,
        public ?Throwable $previous = null
    )
    {
        $reflector = new ReflectionClass(ResponseException::class);
        app(ExceptionHandler::class)->report($reflector->newInstanceArgs(func_get_args()));
    }
}
