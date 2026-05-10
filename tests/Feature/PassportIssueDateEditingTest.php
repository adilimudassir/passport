<?php

use App\Livewire\PassportCapture;
use App\Models\Passport;
use Livewire\Livewire;

test('issue date validity can be updated from the modal and persists immediately', function () {
    $passport = Passport::create([
        'lga' => 'Bodinga',
        'lastname' => 'ABUBAKAR',
        'givennames' => 'AI',
        'gender' => 'F',
        'date_of_birth' => '01-01-63',
        'expiry_date' => '04-10-33',
        'passport_number' => 'A13325080',
        'nationality' => 'NIGERIAN',
    ]);

    Livewire::test(PassportCapture::class)
        ->call('openIssueDateModal', $passport->id)
        ->assertSet('showIssueDateModal', true)
        ->call('updateIssueDateValidity', 10)
        ->assertSet('showIssueDateModal', false);

    expect($passport->fresh()->validity_years)->toBe(10)
        ->and($passport->fresh()->issue_date)->toBe('05/10/2023');
});
