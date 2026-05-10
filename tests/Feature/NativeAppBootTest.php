<?php

use App\Providers\NativeAppServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Native\Laravel\Facades\Window;

test('native app boot runs migrations before opening the main window', function () {
    $pendingWindow = \Mockery::mock();
    $pendingWindow->shouldReceive('title')->once()->with('Passport Data Capture - Sokoto State')->andReturnSelf();
    $pendingWindow->shouldReceive('width')->once()->with(1200)->andReturnSelf();
    $pendingWindow->shouldReceive('height')->once()->with(800)->andReturnSelf();
    $pendingWindow->shouldReceive('minWidth')->once()->with(900)->andReturnSelf();
    $pendingWindow->shouldReceive('minHeight')->once()->with(600)->andReturnSelf();
    $pendingWindow->shouldReceive('maximized')->once()->andReturnSelf();

    Window::shouldReceive('open')
        ->once()
        ->withNoArgs()
        ->andReturn($pendingWindow);

    Artisan::shouldReceive('call')
        ->once()
        ->with('migrate', [
            '--force' => true,
            '--no-interaction' => true,
        ]);

    app(NativeAppServiceProvider::class)->boot();
});
