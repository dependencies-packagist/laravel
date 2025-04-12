<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Jundayw\Render\Facades\Render;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Render::macro('signed', function (string $signature = 'signature', string $algo = 'md5') {
            $data = $this->with('algo', $algo)->all();
            ksort($data);
            return $this->with($signature, hash($algo, http_build_query($data)));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
