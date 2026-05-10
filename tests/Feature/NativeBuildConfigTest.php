<?php

test('nativephp prebuild scripts run migrations before the frontend build', function () {
    expect(config('nativephp.prebuild'))->toBe([
        'php artisan migrate --force',
        'npm run build',
    ]);
});
