<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Events\DiagnosingHealth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Throwable;

class HealthController extends Controller
{
    /**
     * @throws Throwable
     */
    public function __invoke(): Response
    {
        try {
            $exception = null;
            Event::dispatch(new DiagnosingHealth);
        } catch (Throwable $e) {
            if (app()->hasDebugModeEnabled()) {
                throw $e;
            }

            report($e);

            $exception = $e->getMessage();
        }

        return response()->view(view: 'health-up', data: [
            'exception' => $exception,
        ], status: $exception ? 500 : 200);
    }
}
