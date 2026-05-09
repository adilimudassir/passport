<?php

use App\Models\Passport;

test('issue date is derived from expiry date using the passport validity rule', function () {
    $passport = new Passport([
        'expiry_date' => '04-10-28',
    ]);

    expect($passport->issue_date)->toBe('05-10-23');
});
