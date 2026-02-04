<?php

namespace App\Providers;

use Native\Laravel\Facades\Window;
use Native\Laravel\Contracts\ProvidesPhpIni;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        Window::open()
            ->title('Passport Data Capture - Sokoto State')
            ->width(1200)
            ->height(800)
            ->minWidth(900)
            ->minHeight(600)
            ->maximized();
    }

    /**
     * Return an array of php.ini directives to be set.
     */
    public function phpIni(): array
    {
        return [
            'memory_limit' => '256M',
            'max_execution_time' => '300',
        ];
    }
}
