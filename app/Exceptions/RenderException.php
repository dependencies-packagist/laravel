<?php

namespace App\Exceptions;

use Illuminate\Contracts\Debug\ShouldntReport;

class RenderException extends ResponseException implements ShouldntReport
{
    //
}
