<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use App\Listeners\LogSuccessfulLogin;
use Filament\Facades\Filament;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, LogSuccessfulLogin::class);
        
        // CSS personalizado para formulario de blog posts
        Filament::serving(function () {
            Filament::registerRenderHook(
                'panels::head.end',
                fn (): string => '<style>
                    .fi-ta-panel-form-container {
                        max-width: 100% !important;
                        width: 100% !important;
                    }
                    .fi-ta-form-container {
                        max-width: 100% !important;
                        width: 100% !important;
                    }
                </style>'
            );
        });
    }
}
