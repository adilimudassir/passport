<?php

use App\Models\Passport;

test('issue date is derived from expiry date using the passport validity rule', function () {
    $passport = new Passport([
        'expiry_date' => '240818',
    ]);

    expect($passport->issue_date)->toBe('190819');
});
