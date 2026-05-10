<?php

use App\Models\Passport;

test('issue date is derived from expiry date using the default five year validity rule', function () {
    $passport = new Passport([
        'expiry_date' => '04-10-28',
    ]);

    expect($passport->issue_date)->toBe('05/10/2023');
});

test('issue date is derived from expiry date using the stored validity years', function () {
    $passport = new Passport([
        'expiry_date' => '09-02-34',
        'validity_years' => 10,
    ]);

    expect($passport->issue_date)->toBe('10/02/2024');
});

test('stored passport dates are presented in dd/mm/yyyy format', function () {
    $passport = new Passport([
        'date_of_birth' => '01-03-73',
        'expiry_date' => '09-02-29',
    ]);

    expect($passport->date_of_birth)->toBe('01/03/1973')
        ->and($passport->expiry_date)->toBe('09/02/2029');
});
