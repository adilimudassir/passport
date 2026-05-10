<?php

use Illuminate\Support\Facades\Route;

test('native app requests show detailed exception diagnostics for server errors', function () {
    config([
        'app.debug' => false,
        'nativephp-internal.running' => true,
    ]);

    Route::get('/__native-error-test', function () {
        throw new RuntimeException('Native error visibility test');
    });

    $response = $this->get('/__native-error-test');

    $response->assertStatus(500)
        ->assertSeeText('Application Error')
        ->assertSeeText('Native error visibility test')
        ->assertSeeText('RuntimeException')
        ->assertSeeText('Stack Trace');
});

test('non-native requests keep the generic server error response', function () {
    config([
        'app.debug' => false,
        'nativephp-internal.running' => false,
    ]);

    Route::get('/__web-error-test', function () {
        throw new RuntimeException('Web error visibility test');
    });

    $response = $this->get('/__web-error-test');

    $response->assertStatus(500)
        ->assertDontSeeText('Web error visibility test')
        ->assertDontSeeText('Stack Trace');
});
