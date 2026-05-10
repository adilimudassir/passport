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

test('already slash-formatted passport dates do not crash formatting', function () {
    $passport = new Passport([
        'date_of_birth' => '01/03/1973',
        'expiry_date' => '09/02/2029',
        'validity_years' => 5,
    ]);

    expect($passport->date_of_birth)->toBe('01/03/1973')
        ->and($passport->expiry_date)->toBe('09/02/2029')
        ->and($passport->issue_date)->toBe('10/02/2024');
});

test('malformed stored passport dates fail safely instead of throwing during rendering', function () {
    $passport = new Passport([
        'date_of_birth' => '01 MAR 73',
        'expiry_date' => 'not-a-date',
    ]);

    expect(fn () => [$passport->date_of_birth, $passport->expiry_date, $passport->issue_date])->not->toThrow(\Throwable::class)
        ->and($passport->date_of_birth)->toBe('01 MAR 73')
        ->and($passport->expiry_date)->toBe('not-a-date')
        ->and($passport->issue_date)->toBeNull();
});
